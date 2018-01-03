<?php

namespace Centipede;

use Centipede\Enum\Http\Header;
use Centipede\Service\ErrorHandling\AbstractErrorHandlingService;
use Centipede\Service\ErrorHandling\ErrorHandlingServiceContainer;
use Centipede\Service\Logging\AbstractLoggingService;
use Centipede\Service\Logging\LoggingServiceContainer;
use Centipede\Service\Messaging\MessagingService;
use Centipede\Service\Proxy\AbstractProxyService;
use Centipede\Service\Queueing\SimpleQueueingService;
use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;
use Centipede\Service\Queueing\AbstractQueueingService;
use Centipede\Service\Storage\AbstractStorageService;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use InvalidArgumentException;
use Exception;

/**
 * Class Client
 * @package Centipede
 */
final class Client
{
    /**
     * @var AbstractService[]
     */
    private $services;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->services = [];

        $this->client = new GuzzleClient($config);
    }

    /**
     * @return int
     */
    public function getDefaultConcurrency() : int
    {
        return 5;
    }

    /**
     * @param AbstractService $service
     * @return Client
     */
    public function addService(AbstractService $service) : self
    {
        $identifier = $service->getServiceIdentifier();

        if (!array_key_exists($identifier, $this->services)) {
            $this->services[$identifier] = $service;
        }

        return $this;
    }

    /**
     * @param AbstractProxyService $proxyService
     * @return Client
     */
    public function addProxy(AbstractProxyService $proxyService) : self
    {
        return $this->addService($proxyService);
    }

    /**
     * @param AbstractLoggingService $loggingService
     * @return Client
     */
    public function addLogger(AbstractLoggingService $loggingService) : self
    {
        return $this->addService($loggingService);
    }

    /**
     * @param AbstractStorageService $storageService
     * @return Client
     */
    public function addStorage(AbstractStorageService $storageService) : self
    {
        return $this->addService($storageService);
    }

    /**
     * @param AbstractErrorHandlingService $errorHandlingService
     * @return Client
     */
    public function addErrorHandlingService(AbstractErrorHandlingService $errorHandlingService) : self
    {
        return $this->addService($errorHandlingService);
    }

    /**
     * @param callable $responseCallback
     * @param callable $errorCallback
     * @param array<string,mixed> $options
     * @param Request[] $requests
     */
    public function run(callable $responseCallback, ?callable $errorCallback, array $options, Request... $requests) : void
    {
        if (empty($requests)) {
            return;
        }

        $messages = array_map(function (Request $request) {
            return new MessagingService($request);
        }, $requests);

        $queue = new SimpleQueueingService();
        $queue->addMessages($messages);

        $this->process($queue, $responseCallback, $errorCallback, $options);
    }

    /**
     * @param AbstractQueueingService $queue
     * @param callable $responseCallback
     * @param callable $errorCallback
     * @param array $options
     * @return void
     */
    public function process(
        AbstractQueueingService $queue,
        callable $responseCallback = null,
        callable $errorCallback = null,
        array $options = []
    ) : void {
        $limit = null;

        if (array_key_exists('limit', $options)) {
            $limit = (int) $options['limit'];
        }

        $messages = $queue->getMessages($limit);

        if ($messages === null) {
            return;
        }

        $client = new GuzzleClient();

        /**
         * @yield PromiseInterface
         */
        $getPromises = function () use ($options, $queue, $client, $messages) {
            foreach ($messages as $message) {
                if ($proxy = $this->getRandomProxy()) {
                    $options[RequestOptions::PROXY] = (string) $proxy;
                }

                if ($queue->deleteMessage($message)) {
                    $this->getLoggingServiceContainer()->log("
                        Deleted message {$message->getIdentifier()} from queue {$queue->getIdentifier()}
                    ");
                }

                /** @var Request $request */
                $request = $message->getRequest();

                if (
                    $request->getBody() !== null &&
                    $request->hasHeader('Content-Type') &&
                    in_array('application/x-www-form-urlencoded', $request->getHeader('Content-Type'))
                ) {
                    $options['form_params'] = parse_str((string) $request->getBody());
                }

                if (
                    $request->hasHeader('Cookie') &&
                    $cookies = $request->getHeader('Cookie')
                ) {
                    $cookieJar = new CookieJar();

                    foreach ($cookies as $cookieName => $cookieValue) {
                        $setCookie = new SetCookie();
                        $setCookie->setName($cookieName);
                        $setCookie->setValue($cookieValue);
                        $setCookie->setDomain(parse_url($request->getUri(), PHP_URL_HOST));

                        $cookieJar->setCookie($setCookie);
                    }

                    $options['cookies'] = $cookieJar;
                }

                yield $message->getIdentifier() => $client->sendAsync($request, $options);
            }
        };

        $config = [
            'concurrency' => $options['concurrency'] ?? $this->getDefaultConcurrency(),
        ];

        if ($responseCallback !== null) {
            $config['fulfilled'] = function (
                ResponseInterface $response,
                string $messageIdentifier
            ) use ($responseCallback) : void {
                // Add the message identifier to the response headers so it can be retraced
                $response = $response->withHeader(Header::MESSAGE_IDENTIFIER, $messageIdentifier);

                $responseCallback($response, $messageIdentifier);

                // Free up memory once we've handled the response
                if (gc_enabled()) {
                    gc_collect_cycles();
                }
            };
        }

        if ($errorCallback !== null) {
            $config['rejected'] = function (
                Exception $exception,
                string $messageIdentifier
            ) use ($errorCallback) {
                $errorCallback($exception, $messageIdentifier);
            };
        }

        (new EachPromise($getPromises(), $config))->promise()->wait();
    }

    /**
     * @return AbstractProxyService
     */
    public function getRandomProxy() : ?AbstractProxyService
    {
        $proxyServices = $this->getServices(ServiceType::PROXY);

        switch (count($proxyServices)) {
            case 0:
                return null;
            case 1:
                return reset($proxyServices);
            default:
                return $proxyServices[array_rand($proxyServices)];
        }
    }

    /**
     * @return LoggingServiceContainer
     */
    public function getLoggingServiceContainer() : LoggingServiceContainer
    {
        $services = $this->getServices(ServiceType::LOGGING);

        return new LoggingServiceContainer($services);
    }

    /**
     * @return ErrorHandlingServiceContainer
     */
    public function getErrorHandlingServiceContainer() : ErrorHandlingServiceContainer
    {
        $services = $this->getServices(ServiceType::ERROR_HANDLING);

        return new ErrorHandlingServiceContainer($services);
    }

    /**
     * @param string $type
     * @return AbstractService[]
     */
    private function getServices(string $type = null) : array
    {
        if ($type !== null) {
            if (!ServiceType::validate($type)) {
                throw new InvalidArgumentException("Invalid service type '$type'");
            }

            return array_filter($this->services, function (AbstractService $service) use ($type) {
                return $service->getServiceType() === $type;
            });
        }

        return $this->services;
    }
}

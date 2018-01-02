<?php

namespace Centipede\Service\ErrorHandling;

use Bugsnag\Client;
use Bugsnag\Handler;
use Bugsnag\Report;
use Exception;

/**
 * Class BugsnagService
 * @package Centipede\Service\ErrorHandling
 */
class BugsnagService extends AbstractErrorHandlingService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->client = Client::make($apiKey);
        $this->setErrorHandler();
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerError(Exception $exception, array $context = []) : void
    {
        $this->client->notifyException($exception, function (Report $report) use ($context) {
            $report->setSeverity('error');

            if (!empty($context)) {
                $report->setMetaData($context);
            }
        });
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerFatalError(Exception $exception, array $context = []) : void
    {
        $this->registerError($exception, $context);
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function registerWarning(string $message, array $context = []) : void
    {
        $this->client->notifyError('Warning', $message, function (Report $report) use ($context) {
            $report->setSeverity('warning');

            if (!empty($context)) {
                $report->setMetaData($context);
            }
        });
    }

    /**
     * @return void
     */
    protected function setErrorHandler() : void
    {
        Handler::register($this->client);
    }
}

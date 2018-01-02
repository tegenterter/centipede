<?php

namespace Centipede\Service\ErrorHandling;

use Exception;
use Raven_Client;
use Raven_ErrorHandler;

/**
 * Class SentryService
 * @package Centipede\Service\ErrorHandling
 */
class SentryService extends AbstractErrorHandlingService
{
    /**
     * @const string
     */
    public const ERROR_LEVEL_WARNING = 'warning';

    /**
     * @const string
     */
    public const ERROR_LEVEL_ERROR = 'error';

    /**
     * @const string
     */
    public const ERROR_LEVEL_FATAL = 'fatal';

    /**
     * @var Raven_Client
     */
    protected $client;

    /**
     * @var Raven_ErrorHandler
     */
    protected $errorHandler;

    /**
     * @param string $ravenClientUrl
     */
    public function __construct(string $ravenClientUrl)
    {
        $this->client = new Raven_Client($ravenClientUrl);
        $this->setErrorHandler();
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function registerWarning(string $message, array $context = []) : void
    {
        $this->client->captureMessage($message, [], [
            'level' => self::ERROR_LEVEL_WARNING,
            'extra' => $context,
        ]);
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerError(Exception $exception, array $context = []) : void
    {
        $this->client->captureException($exception, [
            'level' => self::ERROR_LEVEL_ERROR,
            'extra' => $context,
        ]);
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerFatalError(Exception $exception, array $context = []) : void
    {
        $this->client->captureException($exception, [
            'level' => self::ERROR_LEVEL_FATAL,
            'extra' => $context,
        ]);
    }

    /**
     * @return void
     */
    protected function setErrorHandler() : void
    {
        $this->errorHandler = new Raven_ErrorHandler($this->client);
        $this->errorHandler->registerExceptionHandler();
        $this->errorHandler->registerErrorHandler();
        $this->errorHandler->registerShutdownFunction();
    }
}

<?php

namespace Centipede\Service\ErrorHandling;

use Exception;

/**
 * Class ErrorHandlingServiceContainer
 * @package Centipede\Service\ErrorHandling
 */
class ErrorHandlingServiceContainer implements ErrorHandlingServiceInterface
{
    /**
     * @var AbstractErrorHandlingService[]
     */
    protected $services;

    /**
     * @param AbstractErrorHandlingService[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function registerWarning(string $message, array $context = []) : void
    {
        foreach ($this->services as $service) {
            $service->registerWarning($message, $context);
        }
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerError(Exception $exception, array $context = []) : void
    {
        foreach ($this->services as $service) {
            $service->registerError($exception, $context);
        }
    }

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerFatalError(Exception $exception, array $context = []) : void
    {
        foreach ($this->services as $service) {
            $service->registerError($exception, $context);
        }
    }
}

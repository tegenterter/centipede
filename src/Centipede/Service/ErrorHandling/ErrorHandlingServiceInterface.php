<?php

namespace Centipede\Service\ErrorHandling;

use Exception;

/**
 * Interface ErrorHandlingServiceInterface
 * @package Centipede\Service\ErrorHandling
 */
interface ErrorHandlingServiceInterface
{
    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    public function registerWarning(string $message, array $context = []) : void;

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerError(Exception $exception, array $context = []) : void;

    /**
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function registerFatalError(Exception $exception, array $context = []) : void;
}

<?php

namespace Centipede\Service\Logging;

/**
 * Interface LoggingServiceInterface
 * @package Centipede\Adapter\Logging
 */
interface LoggingServiceInterface
{
    /**
     * @param string $message
     * @param int $timestamp
     * @return void
     */
    function log(string $message, int $timestamp = null) : void;
}

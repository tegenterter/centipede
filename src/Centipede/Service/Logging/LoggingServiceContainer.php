<?php

namespace Centipede\Service\Logging;

/**
 * Class LoggingServiceContainer
 * @package Centipede\Service\Logging
 */
class LoggingServiceContainer implements LoggingServiceInterface
{
    /**
     * @var AbstractLoggingService[]
     */
    protected $services;

    /**
     * @param AbstractLoggingService[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @inheritdoc
     */
    public function log(string $message, int $timestamp = null) : void
    {
        foreach ($this->services as $service) {
            $service->log($message, $timestamp);
        }
    }
}

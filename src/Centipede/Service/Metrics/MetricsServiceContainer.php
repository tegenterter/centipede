<?php

namespace Centipede\Service\Metrics;

/**
 * Class MetricsServiceContainer
 * @package Centipede\Service\Metrics
 */
class MetricsServiceContainer implements MetricsServiceInterface
{
    /**
     * @var AbstractMetricsService[]
     */
    protected $services;

    /**
     * @param AbstractMetricsService[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @inheritdoc
     */
    public function push(string $namespace, string $key, int $value, int $timestamp = null) : bool
    {
        $success = true;

        foreach ($this->services as $service) {
            if (!$service->push($namespace, $key, $value, $timestamp)) {
                $success = false;
            }
        }

        return $success;
    }
}

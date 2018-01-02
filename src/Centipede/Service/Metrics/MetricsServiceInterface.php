<?php

namespace Centipede\Service\Metrics;

/**
 * Interface MetricsServiceInterface
 * @package Centipede\Service\Metrics
 */
interface MetricsServiceInterface
{
    /**
     * @param string $namespace
     * @param string $key
     * @param int $value
     * @param int $timestamp
     * @return bool
     */
    function push(string $namespace, string $key, int $value, int $timestamp = null) : bool;
}

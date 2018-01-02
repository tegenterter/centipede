<?php

namespace Centipede\Service\Metrics;

use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;

/**
 * Class AbstractMetricsService
 * @package Centipede\Service\Logging
 */
abstract class AbstractMetricsService extends AbstractService implements MetricsServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::METRICS;
    }
}

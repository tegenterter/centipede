<?php

namespace Centipede\Service\Logging;

use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;

/**
 * Class AbstractLoggingService
 * @package Centipede\Adapter\Logging
 */
abstract class AbstractLoggingService extends AbstractService implements LoggingServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::LOGGING;
    }
}

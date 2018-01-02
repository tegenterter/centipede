<?php

namespace Centipede\Service\ErrorHandling;

use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;

/**
 * Class AbstractErrorHandlingService
 * @package Centipede\Service\ErrorHandling
 */
abstract class AbstractErrorHandlingService extends AbstractService implements ErrorHandlingServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::ERROR_HANDLING;
    }
}

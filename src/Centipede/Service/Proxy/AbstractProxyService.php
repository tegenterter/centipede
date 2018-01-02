<?php

namespace Centipede\Service\Proxy;

use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;

/**
 * Class AbstractProxyService
 * @package Centipede\Service\Proxy
 */
abstract class AbstractProxyService extends AbstractService implements ProxyServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::PROXY;
    }
}

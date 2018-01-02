<?php

namespace Centipede\Service\Proxy;

/**
 * Interface ProxyServiceInterface
 * @package Centipede\Service\Proxy
 */
interface ProxyServiceInterface
{
    /**
     * @return string
     */
    function getProxyType() : string;

    /**
     * @return string
     */
    function getProxy() : string;

    /**
     * @return string
     */
    function getAuth() : ?string;

    /**
     * @return string
     */
    function __toString() : string;
}

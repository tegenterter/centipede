<?php

namespace Centipede\Service;

use Psr\Container\ContainerInterface;

/**
 * Interface ServiceInterface
 * @package Centipede\Service
 */
interface ServiceInterface
{
    /**
     * @return string
     */
    function getServiceType() : string;

    /**
     * @return string
     */
    function getServiceIdentifier() : string;

    /**
     * @param ContainerInterface $container
     * @return ServiceInterface
     */
    function setContainer(ContainerInterface $container) : self;
}

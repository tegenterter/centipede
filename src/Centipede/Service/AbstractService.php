<?php

namespace Centipede\Service;

use Psr\Container\ContainerInterface;

/**
 * Class Service
 * @package Centipede\Service
 */
abstract class AbstractService implements ServiceInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @inheritdoc
     */
    public function getServiceIdentifier() : string
    {
        return md5(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container) : ServiceInterface
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getContainer() : ?ContainerInterface
    {
        return $this->container;
    }
}

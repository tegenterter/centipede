<?php

namespace Centipede\Service;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * Class ServiceNotFoundException
 * @package Centipede\Service
 */
class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
{
}

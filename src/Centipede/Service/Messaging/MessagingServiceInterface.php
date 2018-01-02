<?php

namespace Centipede\Service\Messaging;

use Psr\Http\Message\RequestInterface;

/**
 * Interface MessagingServiceInterface
 * @package Centipede\Service\Messaging
 */
interface MessagingServiceInterface
{
    /**
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface;

    /**
     * @return array
     */
    public function toArray() : array;
}

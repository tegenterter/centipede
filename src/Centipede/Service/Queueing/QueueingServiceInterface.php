<?php

namespace Centipede\Service\Queueing;

use Centipede\Service\Messaging\MessagingService;
use Centipede\Service\Messaging\MessagingServiceInterface;
use Generator;

/**
 * Interface QueueingServiceInterface
 * @package Centipede\Service\Queueing
 */
interface QueueingServiceInterface
{
    /**
     * @return string
     */
    function getIdentifier() : string;

    /**
     * @param int $limit
     * @return Generator
     */
    function getMessages(int $limit = null) : ?Generator;

    /**
     * @param MessagingServiceInterface $message
     * @param array $attributes
     * @return QueueingServiceInterface
     */
    function addMessage(MessagingServiceInterface $message, array $attributes = []) : QueueingServiceInterface;

    /**
     * @param MessagingService[] $messages
     * @return QueueingServiceInterface
     */
    function addMessages(array $messages) : QueueingServiceInterface;

    /**
     * @param MessagingServiceInterface $message
     * @return bool
     */
    function deleteMessage(MessagingServiceInterface $message) : bool;
}

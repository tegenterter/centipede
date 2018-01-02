<?php

namespace Centipede\Service\Queueing;

use Centipede\Service\Messaging\MessagingService;
use Centipede\Service\Messaging\MessagingServiceInterface;
use Generator;

/**
 * Class SimpleQueueingService
 * @package Centipede\Service\Queueing
 */
class SimpleQueueingService extends AbstractQueueingService
{
    /**
     * @var MessagingService[] $messages
     */
    protected $messages;

    /**
     * @inheritdoc
     */
    public function __construct(string $identifier = null)
    {
        parent::__construct($identifier);

        $this->messages = [];
    }

    /**
     * @inheritdoc
     */
    public function getMessages(int $limit = null) : ?Generator
    {
        $count = 0;

        foreach ($this->messages as $message) {
            yield $message;

            $count++;

            if ($limit === $count) {
                break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function addMessage(MessagingServiceInterface $message, array $attributes = []) : QueueingServiceInterface
    {
        $this->messages[$message->getIdentifier()] = $message;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function deleteMessage(MessagingServiceInterface $message) : bool
    {
        if (array_key_exists($message->getIdentifier(), $this->messages)) {
            unset($this->messages[$message->getIdentifier()]);

            return true;
        }

        return false;
    }
}

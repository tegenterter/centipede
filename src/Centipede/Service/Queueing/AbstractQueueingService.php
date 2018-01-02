<?php

namespace Centipede\Service\Queueing;

use Centipede\Service\AbstractService;
use Centipede\Enum\ServiceType;
use Centipede\Service\Messaging\MessagingService;

/**
 * Class AbstractQueueingService
 * @package Centipede\Service\Queueing
 */
abstract class AbstractQueueingService extends AbstractService implements QueueingServiceInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier = null)
    {
        $this->setIdentifier($identifier ?: uniqid());
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return void
     */
    private function setIdentifier(string $identifier) : void
    {
        $this->identifier = $identifier;
    }

    /**
     * @param MessagingService[] $messages
     * @return QueueingServiceInterface
     */
    public function addMessages(array $messages) : QueueingServiceInterface
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::QUEUE;
    }
}

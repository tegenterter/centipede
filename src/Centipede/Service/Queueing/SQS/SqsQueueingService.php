<?php

namespace Centipede\Service\Queueing\SQS;

use Aws\Result;
use Aws\Sqs\SqsClient;
use Centipede\Service\Messaging\MessagingServiceInterface;
use Centipede\Service\Messaging\SQS\SqsMessagingService;
use Centipede\Service\Queueing\AbstractQueueingService;
use Centipede\Service\Queueing\QueueingServiceInterface;
use Generator;
use InvalidArgumentException;

/**
 * Class SqsQueueingService
 * @package Centipede\Service\Queueing
 */
class SqsQueueingService extends AbstractQueueingService
{
    /**
     * @var SqsClient
     */
    protected $client;

    /**
     * @param string $key
     * @param string $secret
     * @param string $region
     * @param string $queueUrl
     */
    public function __construct(string $key, string $secret, string $region, string $queueUrl)
    {
        parent::__construct($queueUrl);

        $this->setClient($key, $secret, $region);
    }

    /**
     * @inheritdoc
     */
    public function getMessages(int $limit = null) : ?Generator
    {
        $count = 0;

        while (true) {
            $result = $this->client->receiveMessage([
                'QueueUrl' => $this->getQueueUrl(),
                'MaxNumberOfMessages' => $limit ?: 10,
                'MessageAttributeNames' => [
                    'Identifier',
                ],
            ]);

            $messages = $result->get('Messages');

            if (empty($messages)) {
                break;
            }

            foreach ($messages as $message) {
                $body = json_decode($message['Body'], true);

                yield $this->decodeMessage(array_merge($message, $body));

                $count++;

                if ($count === $limit) {
                    break 2;
                }
            }
        }
    }

    /**
     * @param MessagingServiceInterface $message
     * @param array $attributes
     * @return QueueingServiceInterface
     * @throws InvalidArgumentException
     */
    public function addMessage(MessagingServiceInterface $message, array $attributes = []) : QueueingServiceInterface
    {
        if (!$message instanceof SqsMessagingService) {
            throw new InvalidArgumentException('Invalid message');
        }

        $attributes = array_merge([
            'Identifier' => [
                'DataType' => 'String',
                'StringValue' => $message->getIdentifier(),
            ],
        ], $attributes);

        $this->client->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => json_encode($message->toArray()),
            'MessageAttributes' => $attributes,
        ]);

        return $this;
    }

    /**
     * @param MessagingServiceInterface $message
     * @return bool
     * @throws InvalidArgumentException
     */
    public function deleteMessage(MessagingServiceInterface $message) : bool
    {
        if (!$message instanceof SqsMessagingService) {
            throw new InvalidArgumentException('Invalid message');
        }

        if ($message->getReceiptHandle() === null) {
            throw new InvalidArgumentException('Missing receipt handle');
        }

        $result = $this->client->deleteMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'ReceiptHandle' => $message->getReceiptHandle(),
        ]);

        return $result instanceof Result;
    }

    /**
     * @param string $key
     * @param string $secret
     * @param string $region
     * @return void
     */
    protected function setClient(string $key, string $secret, string $region) : void
    {
        $this->client = SqsClient::factory([
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
            'region' => $region,
            'version' => 'latest',
        ]);
    }

    /**
     * @return string
     */
    protected function getQueueUrl() : string
    {
        return $this->getIdentifier();
    }

    /**
     * @param array $values
     * @return SqsMessagingService
     */
    protected function decodeMessage(array $values) : SqsMessagingService
    {
        return SqsMessagingService::fromArray($values);
    }
}

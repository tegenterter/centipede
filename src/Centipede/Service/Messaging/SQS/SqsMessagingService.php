<?php

namespace Centipede\Service\Messaging\SQS;

use Centipede\Factory\RequestFactory;
use Centipede\Service\Messaging\MessagingService;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

/**
 * Class SqsMessagingService
 * @package Centipede\Service\Messaging\S3
 */
class SqsMessagingService extends MessagingService
{
    /**
     * @var string
     */
    protected $receiptHandle;

    /**
     * @param array $values
     * @return SqsMessagingService
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $values) : self
    {
        if (
            !isset($values['Request']) ||
            !isset($values['ReceiptHandle']) ||
            !isset($values['MessageAttributes']['Identifier']['StringValue'])
        ) {
            throw new InvalidArgumentException('Invalid message');
        }

        return new self(
            RequestFactory::fromArray($values['Request']),
            $values['MessageAttributes']['Identifier']['StringValue'],
            $values['ReceiptHandle']
        );
    }

    /**
     * @param RequestInterface $request
     * @param string $identifier
     * @param string $receiptHandle
     */
    public function __construct(RequestInterface $request, string $identifier, string $receiptHandle = null)
    {
        parent::__construct($request, $identifier);

        if ($receiptHandle !== null) {
            $this->setReceiptHandle($receiptHandle);
        }
    }

    /**
     * @inheritdoc
     */
    public function toArray() : array
    {
        return [
            'Request' => RequestFactory::toArray($this->getRequest()),
        ];
    }

    /**
     * @return string
     */
    public function getReceiptHandle() : string
    {
        return $this->receiptHandle;
    }

    /**
     * @param string $receiptHandle
     * @return void
     */
    protected function setReceiptHandle(string $receiptHandle) : void
    {
        $this->receiptHandle = $receiptHandle;
    }
}

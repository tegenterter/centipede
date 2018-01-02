<?php

namespace Centipede\Service\Messaging;

use Centipede\Factory\RequestFactory;
use Centipede\Service\AbstractService;
use Centipede\Enum\ServiceType;
use Psr\Http\Message\RequestInterface;

/**
 * Class MessagingService
 * @package Centipede\Service\Messaging
 */
class MessagingService extends AbstractService implements MessagingServiceInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param RequestInterface $request
     * @param string $identifier
     */
    public function __construct(RequestInterface $request, string $identifier = null)
    {
        $this->setRequest($request);

        if ($identifier === null) {
            $identifier = md5(serialize($request));
        }

        $this->setIdentifier($identifier);
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::MESSAGE;
    }

    /**
     * @inheritdoc
     */
    public function toArray() : array
    {
        return [
            'Identifier' => $this->getIdentifier(),
            'Request' => RequestFactory::toArray($this->getRequest()),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setIdentifier(string $identifier) : void
    {
        $this->identifier = $identifier;
    }

    /**
     * @param RequestInterface $request
     * @return void
     */
    protected function setRequest(RequestInterface $request) : void
    {
        $this->request = $request;
    }
}

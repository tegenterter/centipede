<?php

namespace Centipede\Service\Proxy;

/**
 * Class Socks4Service
 * @package Centipede\Service\Proxy
 */
class Socks4Service extends AbstractProxyService
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @inheritdoc
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getProxyType() : string
    {
        return 'socks4';
    }

    /**
     * @inheritdoc
     */
    public function getProxy() : string
    {
        return $this->host . ':' . $this->port;
    }

    /**
     * @return string
     */
    public function getAuth() : ?string
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function __toString() : string
    {
        if ($auth = $this->getAuth()) {
            $auth .= '@';
        }

        return $this->getProxyType() . '://' . $auth .  $this->getProxy();
    }
}

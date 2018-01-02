<?php

namespace Centipede\Factory;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class RequestFactory
 * @package Centipede\Factory
 */
final class RequestFactory
{
    /**
     * @param RequestInterface $request
     * @return array
     */
    public static function toArray(RequestInterface $request) : array
    {
        $values = [
            'Method' => $request->getMethod(),
            'Uri' => (string) $request->getUri(),
            'Headers' => $request->getHeaders(),
        ];

        if (!empty($request->getBody()->getContents())) {
            $values['Body'] = (string) $request->getBody();
        }

        return $values;
    }

    /**
     * @param array $data
     * @return RequestInterface
     */
    public static function fromArray(array $data) : RequestInterface
    {
        return new Request(
            $data['Method'],
            $data['Uri'],
            $data['Headers'],
            $data['Body'] ?? null
        );
    }
}

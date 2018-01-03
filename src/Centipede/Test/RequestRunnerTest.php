<?php

namespace Centipede\Test;

use Exception;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestRunnerTest
 */
class RequestRunnerTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testSingleRequest() : void
    {
        $this->client->run(
            function (ResponseInterface $response) {
                $this->assertEquals(200, $response->getStatusCode());
                $this->assertTrue(!!preg_match('/<title>Google<\/title>/m', $response->getBody()->getContents()));
            },
            function (Exception $exception) {
                $this->fail($exception->getMessage());
            },
            [],
            new Request('GET', 'https://www.google.com')
        );
    }

    /**
     * @return void
     */
    public function testDisallowRedirects() : void
    {
        $this->client->run(
            function (ResponseInterface $response) {
                $this->assertEquals(301, $response->getStatusCode());
            },
            function (Exception $exception) {
                $this->fail($exception->getMessage());
            },
            [
                'allow_redirects' => false,
            ],
            new Request('GET', 'http://thibaut.sh')
        );
    }

    /**
     * @return void
     */
    public function testAllowRedirects() : void
    {
        $this->client->run(
            function (ResponseInterface $response) {
                $this->assertEquals(200, $response->getStatusCode());
            },
            function (Exception $exception) {
                $this->fail($exception->getMessage());
            },
            [
                'allow_redirects' => true,
            ],
            new Request('GET', 'http://thibaut.sh')
        );
    }
}

<?php

namespace Centipede\Test;

use Exception;
use Centipede\Service\Messaging\MessagingService;
use Centipede\Service\Queueing\SimpleQueueingService;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ConcurrencyTest
 * @package Centipede\Test
 */
class ConcurrencyTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testAsynchronousRequests() : void
    {
        $urls = [
            'https://www.google.com',
            'https://www.facebook.com',
            'https://www.instagram.com',
            'https://www.twitter.com',
            'https://www.microsoft.com',
            'https://www.apple.com',
        ];

        $queue = new SimpleQueueingService();

        foreach ($urls as $index => $url) {
            $queue->addMessage(
                new MessagingService(
                    new Request('GET', $url),
                    $index
                )
            );
        }

        $messageIdentifiers = [];

        $this->client->process(
            $queue,
            function (ResponseInterface $response, string $messageIdentifier) use (&$messageIdentifiers) {
                $this->assertEquals(200, $response->getStatusCode());

                $messageIdentifiers[] = (int) $messageIdentifier;
            },
            function (Exception $exception) {
                $this->fail($exception->getMessage());
            },
            [
                'concurrency' => count($urls),
            ]
        );

        $this->assertNotEquals(
            $messageIdentifiers,
            range(0, count($urls) - 1)
        );
    }
}

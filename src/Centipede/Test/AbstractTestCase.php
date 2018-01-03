<?php

declare(strict_types=1);

namespace Centipede\Test;

use Centipede\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @return void
     */
    protected function setUp() : void
    {
        $this->client = new Client();
    }
}

<?php

namespace Centipede\Service\Logging;

use DateTime;

/**
 * Class OutputService
 * @package Centipede\Service\Logging
 */
class OutputService extends AbstractLoggingService
{
    /**
     * @inheritdoc
     */
    public function log(string $message, int $timestamp = null) : void
    {
        $dateTime = new DateTime();

        if ($timestamp !== null) {
            $dateTime->setTimestamp($timestamp);
        }

        echo $dateTime->format('Y-m-d H:i:s') . ' ' . $message . PHP_EOL;
    }
}

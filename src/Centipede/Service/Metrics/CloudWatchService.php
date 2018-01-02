<?php

namespace Centipede\Service\Metrics;

use Aws\CloudWatch\CloudWatchClient;
use Aws\Result;

/**
 * Class CloudWatchService
 * @package Centipede\Service\Metrics
 */
class CloudWatchService extends AbstractMetricsService
{
    /**
     * @var CloudWatchClient
     */
    protected $client;

    /**
     * @param string $key
     * @param string $secret
     * @param string $region
     */
    public function __construct(string $key, string $secret, string $region)
    {
        $this->setClient($key, $secret, $region);
    }

    /**
     * @inheritdoc
     */
    public function push(string $namespace, string $key, int $value, int $timestamp = null) : bool
    {
        $data = [
            'MetricName' => $key,
            'Value' => $value,
            'Unit' => 'Count',
        ];

        if ($timestamp !== null) {
            $data['Timestamp'] = $timestamp;
        }

        $result = $this->client->putMetricData([
            'Namespace' => $namespace,
            'MetricData' => [
                $data,
            ],
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
        $this->client = CloudWatchClient::factory([
            'key' => $key,
            'secret' => $secret,
            'region' => $region,
        ]);
    }
}

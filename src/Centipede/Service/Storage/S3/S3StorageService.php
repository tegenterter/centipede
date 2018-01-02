<?php

namespace Centipede\Service\Storage\S3;

use Aws\S3\S3Client;
use Centipede\Service\Storage\AbstractStorageService;

/**
 * Class S3StorageService
 * @package Centipede\Service\Storage
 */
class S3StorageService extends AbstractStorageService
{
    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $bucket;

    /**
     * @param string $key
     * @param string $secret
     * @param string $region
     * @param string $bucket
     */
    public function __construct(string $key, string $secret, string $region, string $bucket)
    {
        $this->setClient($key, $secret, $region);
        $this->setBucket($bucket);
    }

    /**
     * @param string $identifier
     * @param mixed $data
     * @return string
     */
    public function put(string $identifier, $data) : string
    {
        $result = $this->getClient()->putObject([
            'Bucket' => $this->getBucket(),
            'Key' => $identifier,
            'Body' => $data,
        ]);

        return $result->get('ObjectURL');
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get(string $identifier)
    {
        $result = $this->getClient()->getObject([
            'Bucket' => $this->getBucket(),
            'Key' => $identifier,
        ]);

        return $result['Body'];
    }

    /**
     * @return string
     */
    protected function getBucket() : string
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     * @return void
     */
    protected function setBucket(string $bucket) : void
    {
        $this->bucket = $bucket;
    }

    /**
     * @return S3Client
     */
    protected function getClient() : S3Client
    {
        return $this->client;
    }

    /**
     * @param string $key
     * @param string $secret
     * @param string $region
     * @return void
     */
    protected function setClient(string $key, string $secret, string $region) : void
    {
        $this->client = S3Client::factory([
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
            'region' => $region,
            'version' => 'latest',
        ]);
    }
}

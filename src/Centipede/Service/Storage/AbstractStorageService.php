<?php

namespace Centipede\Service\Storage;

use Centipede\Enum\ServiceType;
use Centipede\Service\AbstractService;

/**
 * Class AbstractStorageService
 * @package Centipede\Service\Storage
 */
abstract class AbstractStorageService extends AbstractService implements StorageServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getServiceType() : string
    {
        return ServiceType::STORAGE;
    }
}

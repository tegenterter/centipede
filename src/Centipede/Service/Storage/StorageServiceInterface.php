<?php

namespace Centipede\Service\Storage;

/**
 * Interface StorageServiceInterface
 * @package Centipede\Service\Storage
 */
interface StorageServiceInterface
{
    /**
     * @param string $identifier
     * @param mixed $data
     */
    public function put(string $identifier, $data);

    /**
     * @param string $identifier
     */
    public function get(string $identifier);
}

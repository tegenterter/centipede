<?php

namespace Centipede\Enum;

/**
 * Class ServiceType
 * @package Centipede\Enum
 */
class ServiceType extends Enum
{
    /**
     * @const string
     */
    public const PROXY = 'proxy';

    /**
     * @const string
     */
    public const QUEUE = 'queue';

    /**
     * @const string
     */
    public const MESSAGE = 'message';

    /**
     * @const string
     */
    public const LOGGING = 'logging';

    /**
     * @const string
     */
    public const METRICS = 'metrics';

    /**
     * @const string
     */
    public const STORAGE = 'storage';

    /**
     * @const string
     */
    public const ERROR_HANDLING = 'error_handling';
}

<?php

namespace Centipede\Enum\Http;

use Centipede\Enum\Enum;

/**
 * Class Method
 * @package Centipede\Enum\Http
 */
final class Method extends Enum
{
    /**
     * @const string
     */
    public const OPTIONS = 'OPTIONS';

    /**
     * @const string
     */
    public const GET = 'GET';

    /**
     * @const string
     */
    public const HEAD = 'HEAD';

    /**
     * @const string
     */
    public const POST = 'POST';

    /**
     * @const string
     */
    public const PUT = 'PUT';

    /**
     * @const string
     */
    public const DELETE = 'DELETE';

    /**
     * @const string
     */
    public const TRACE = 'TRACE';

    /**
     * @const string
     */
    public const CONNECT = 'CONNECT';
}

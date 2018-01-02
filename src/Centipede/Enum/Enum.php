<?php

namespace Centipede\Enum;

use ReflectionClass;

/**
 * Class Enum
 * @package Centipede\Enum
 */
abstract class Enum
{
    /**
     * @return array
     */
    public static function getValues() : array
    {
        $reflectionClass = new ReflectionClass(get_called_class());

        return $reflectionClass->getConstants();
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function validate(string $value) : bool
    {
        return in_array($value, self::getValues());
    }
}

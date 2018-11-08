<?php

namespace kartavik\Support;

/**
 * Interface StrictInterface
 * @package kartavik\Support
 */
interface StrictInterface
{
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const ARRAYABLE = 'arrayable';
    public const BOOLEAN = 'boolean';
    public const RESOURCE = 'resource';
    public const OBJECT = 'object';

    public function type(): string;

    public function validate($var): bool;

    public static function typeof($var): StrictInterface;

    public static function string(): StrictInterface;

    public static function integer(): StrictInterface;

    public static function float(): StrictInterface;

    public static function arrayable(): StrictInterface;

    public static function boolean(): StrictInterface;

    public static function resource(): StrictInterface;

    public static function object(string $className = StrictInterface::OBJECT): StrictInterface;
}

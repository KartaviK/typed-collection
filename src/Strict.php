<?php

namespace kartavik\Support;

/**
 * Class Strict
 * @package kartavik\Support
 */
final class Strict
{
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const ARRAYABLE = 'arrayable';
    public const BOOLEAN = 'boolean';
    public const RESOURCE = 'resource';
    public const OBJECT = 'object';

    /** @var string */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public function t(): string
    {
        return $this->type;
    }

    public static function availableTypes(): array
    {
        return [
            static::RESOURCE,
            static::BOOLEAN,
            static::ARRAYABLE,
            static::FLOAT,
            static::INTEGER,
            static::OBJECT,
            static::STRING
        ];
    }

    /**
     * @param mixed $var
     *
     * @throws Exception\Validation
     */
    public function validate($var): void
    {
        switch ($this->type) {
            case static::STRING:
                $validated = is_string($var);
                break;
            case static::INTEGER:
                $validated = is_int($var);
                break;
            case static::FLOAT:
                $validated = is_float($var);
                break;
            case static::ARRAYABLE:
                $validated = is_array($var);
                break;
            case static::BOOLEAN:
                $validated = is_bool($var);
                break;
            case static::RESOURCE:
                $validated = is_resource($var);
                break;
            case static::OBJECT:
                $validated = is_object($var);
                break;
            default:
                $validated = $var instanceof $this->type;
        }

        if (!$validated) {
            throw new Exception\Validation($var);
        }
    }

    public static function string(): Strict
    {
        return new Strict(static::STRING);
    }

    public static function integer(): Strict
    {
        return new Strict(static::INTEGER);
    }

    public static function float(): Strict
    {
        return new Strict(static::FLOAT);
    }

    public static function arrayable(): Strict
    {
        return new Strict(static::ARRAYABLE);
    }

    public static function boolean(): Strict
    {
        return new Strict(static::BOOLEAN);
    }

    public static function resource(): Strict
    {
        return new Strict(static::RESOURCE);
    }

    public static function object(string $className = self::OBJECT): Strict
    {
        if ($className === static::OBJECT || class_exists($className)) {
            return new Strict($className);
        }

        throw new Exception\UnprocessedType($className);
    }

    public static function typeof($var): Strict
    {
        $strict = null;

        switch (true) {
            case is_string($var):
                $strict = Strict::string();
                break;
            case is_int($var):
                $strict = Strict::integer();
                break;
            case is_float($var):
                $strict = Strict::float();
                break;
            case is_array($var):
                $strict = Strict::arrayable();
                break;
            case is_resource($var):
                $strict = Strict::resource();
                break;
            case is_bool($var):
                $strict = Strict::boolean();
                break;
            case is_object($var):
                $strict = Strict::object(get_class($var));
                break;
        }

        return $strict;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::object($name);
    }
}

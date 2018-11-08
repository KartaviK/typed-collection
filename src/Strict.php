<?php

namespace kartavik\Support;

use kartavik\Support\Exception\UnprocessedType;

/**
 * Class Strict
 * @package kartavik\Support
 */
class Strict
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

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

    final public function type(): string
    {
        return $this->type;
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

    public static function strictof($var): Strict
    {
        switch (true) {
            case is_string($var):
                return Strict::string();
            case is_int($var):
                return Strict::integer();
            case is_float($var):
                return Strict::float();
            case is_array($var):
                return Strict::arrayable();
            case is_resource($var):
                return Strict::resource();
            case is_bool($var):
                return Strict::boolean();
            case is_object($var):
                return Strict::object(get_class($var));
            default:
                throw new UnprocessedType(gettype($var));
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::object($name);
    }
}

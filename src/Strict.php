<?php

namespace kartavik\Support;

use kartavik\Support\Exception\UnprocessedType;

/**
 * Class Strict
 * @package kartavik\Support
 */
class Strict implements StrictInterface
{
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
     * @return bool
     */
    public function validate($var): bool
    {
        switch ($this->type) {
            case static::STRING:
                return is_string($var);
            case static::INTEGER:
                return is_int($var);
            case static::FLOAT:
                return is_float($var);
            case static::ARRAYABLE:
                return is_array($var);
            case static::BOOLEAN:
                return is_bool($var);
            case static::RESOURCE:
                return is_resource($var);
            case static::OBJECT:
                return is_object($var);
            default:
                return $var instanceof $this->type;
        }
    }

    public static function string(): StrictInterface
    {
        return new static(static::STRING);
    }

    public static function integer(): StrictInterface
    {
        return new static(static::INTEGER);
    }

    public static function float(): StrictInterface
    {
        return new static(static::FLOAT);
    }

    public static function arrayable(): StrictInterface
    {
        return new static(static::ARRAYABLE);
    }

    public static function boolean(): StrictInterface
    {
        return new static(static::BOOLEAN);
    }

    public static function resource(): StrictInterface
    {
        return new static(static::RESOURCE);
    }

    public static function object(string $className = self::OBJECT): StrictInterface
    {
        if ($className === static::OBJECT || class_exists($className)) {
            return new static($className);
        }

        throw new Exception\UnprocessedType($className);
    }

    public static function typeof($var): StrictInterface
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

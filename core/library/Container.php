<?php

namespace core\library;

class Container
{
    private static array $container = [];

    public static function bind(string $key, mixed $value): void
    {
        static::$container[$key] = $value;
    }

    public static function resolve(string $key): ?string
    {
        if (!array_key_exists($key, static::$container)) {
            return null;
        }

        return static::$container[$key];

    }

}
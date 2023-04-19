<?php

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class NestedArray
{
    /**
     * @psalm-param array<array-key> $path
     */
    public static function get(array $array, array $path, mixed &$retval = null): bool
    {
        $current = $array;
        foreach ($path as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return false;
            }
            /** @psalm-var array|mixed */
            $current = $current[$key];
        }
        /** @psalm-var mixed */
        $retval = $current;
        return true;
    }

    /**
     * @psalm-template T
     *
     * @psalm-param T $value
     * @psalm-param-out array|T $array
     * @psalm-param array<array-key> $path
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress UnusedVariable
     */
    public static function set(array &$array, array $path, mixed $value): void
    {
        $current = &$array;
        foreach ($path as $key) {
            if (!array_key_exists($key, $current) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        /** @psalm-var mixed */
        $current = $value;
    }

    /**
     * @psalm-param array<array-key|array> $lookup
     * @psalm-return ?list<array-key>
     */
    public static function lookup(array $array, array $lookup, mixed &$retval = null): ?array
    {
        return self::lookupRecursion($array, [], $lookup, $retval);
    }

    /**
     * @psalm-param list<array-key> $stack
     * @psalm-param array<array-key|array> $lookup
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupRecursion(array $array, array $stack, array $lookup, mixed &$retval): ?array
    {
        if (empty($lookup)) {
            $retval = $array;
            return $stack;
        }

        $head = array_shift($lookup);
        if (!is_array($head)) {
            return self::lookupScalarHead($array, $stack, $head, $lookup, $retval);
        }

        return self::lookupArrayHead($array, $stack, $head, $lookup, $retval);
    }

    /**
     * @psalm-param list<array-key> $stack
     * @psalm-param array-key $head
     * @psalm-param array<array-key|array> $remainder
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupScalarHead(
        array $array,
        array $stack,
        string|int $head,
        array $remainder,
        mixed &$retval
    ): ?array {
        if (!array_key_exists($head, $array)) {
            return null;
        }

        $stack[] = $head;
        if (empty($remainder)) {
            /** @psalm-var mixed */
            $retval = $array[$head];
            return $stack;
        }

        if (!is_array($array[$head])) {
            return null;
        }
        return self::lookupRecursion($array[$head], $stack, $remainder, $retval);
    }

    /**
     * @psalm-param list<array-key> $stack
     * @psalm-param array<array-key|array> $remainder
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupArrayHead(
        array $array,
        array $stack,
        array $head,
        array $remainder,
        mixed &$retval
    ): ?array {

        if (empty($head)) {
            return self::lookupRecursion($array, $stack, $remainder, $retval);
        }

        return self::lookupNonEmptyArrayHead($array, $stack, $head, $remainder, $retval);
    }

    /**
     * @psalm-param list<array-key> $stack
     * @psalm-param non-empty-array $head
     * @psalm-param array<array-key|array> $remainder
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupNonemptyArrayHead(
        array $array,
        array $stack,
        array $head,
        array $remainder,
        mixed &$retval
    ): ?array {
        foreach ($head as $entry) {
            if (is_string($entry) || is_int($entry)) {
                $entry = (array)$entry;
            }
            if (!is_array($entry)) {
                continue;
            }
            // Psalm does not support recursive array types
            // (https://github.com/vimeo/psalm/issues/1892),
            // so we have to cheat it somehow.
            /** @psalm-var array<array-key|array> */
            $lookup = [...$entry, ...$remainder];
            $path = self::lookupRecursion($array, $stack, $lookup, $retval);
            if (null !== $path) {
                return $path;
            }
        }
        return null;
    }
}

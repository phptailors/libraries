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
     * @psalm-param array<array<array-key|array>> $lookup
     * @psalm-param array-key $key
     */
    public static function lookup(array $array, array $lookup, mixed $key, mixed &$retval): ?array
    {
        foreach ($lookup as $prefix) {
            $path = self::lookupRecursion($array, [], $prefix, $deref);
            if (null === $path) {
                continue;
            }
            $path = self::lookupResult($deref, $path, $key, $retval);
            if (null !== $path) {
                return $path;
            }
        }
        return null;
    }

    /**
     * @psalm-param array<array-key|array> $prefix
     * @psalm-param list<array-key> $stack
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupRecursion(array $array, array $stack, array $prefix, mixed &$deref): ?array
    {
        if (empty($prefix)) {
            $deref = $array;
            return [];
        }

        $head = array_shift($remainder = $prefix);
        if (is_int($head) || is_string($head)) {
            if (!array_key_exists($head, $array) || !is_array($array[$head])) {
                return null;
            }
            $stack[] = $head;
            return self::lookupRecursion($array[$head], $stack, $remainder, $deref);
        }

        foreach ($head as $entry) {
            if (!is_string($entry) && !is_int($entry) && !is_array($entry)) {
                continue;
            }
            $newprefix = array_merge((array)$entry, $remainder);
            if (null !== ($path = self::lookupRecursion($array, $stack, $newprefix, $deref))) {
                return $path;
            }
        }
        return null;
    }

    /**
     * @psalm-param list<array-key> $path
     * @psalm-param array-key $key
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupResult(mixed $deref, array $path, mixed $key, mixed &$retval): ?array
    {
        if (!is_array($deref) || !array_key_exists($key, $deref)) {
            return null;
        }
        /** @psalm-var mixed */
        $retval = $deref[$key];
        $path[] = $key;
        return $path;
    }
}

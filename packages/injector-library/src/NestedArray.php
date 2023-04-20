<?php declare(strict_types=1);

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
     *
     * @psalm-param-out array|T $array
     *
     * @psalm-param array<array-key> $path
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress UnusedVariable
     */
    public static function set(array &$array, array $path, mixed $value): void
    {
        $current = &$array;
        foreach ($path as $key) {
            if (!array_key_exists($key, $current)) {
                $current[$key] = [];
            }
            $current = &$current[$key];
            if (!is_array($current)) {
                $current = [];
            }
        }

        /** @psalm-var mixed */
        $current = $value;
    }

    /**
     * @psalm-param array<array-key> $path
     */
    public static function del(array &$array, array $path): void
    {
        if (empty($path)) {
            return;
        }
        $key = array_pop($path);
        self::del2($array, $path, $key);
    }

    /**
     * @psalm-param array<array-key> $path
     * @psalm-param array-key $key
     */
    public static function del2(array &$array, array $path, mixed $key): void
    {
        $current = &$array;
        foreach ($path as $p) {
            if (!is_array($current) || !array_key_exists($p, $current)) {
                return;
            }

            $current = &$current[$p];
        }

        if (is_array($current)) {
            unset($current[$key]);
        }
    }

    /**
     * @psalm-param array<array-key|array> $lookup
     *
     * @psalm-return ?list<array-key>
     */
    public static function lookup(array $array, array $lookup, mixed &$retval = null): ?array
    {
        return self::lookupRecursion($array, [], $lookup, $retval);
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param array<array-key|array> $ahead
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupRecursion(array $array, array $behind, array $ahead, mixed &$retval): ?array
    {
        if (empty($ahead)) {
            $retval = $array;

            return $behind;
        }

        $head = array_shift($ahead);
        if (!is_array($head)) {
            return self::lookupWithScalarHead($array, $behind, $head, $ahead, $retval);
        }

        return self::lookupWithArrayHead($array, $behind, $head, $ahead, $retval);
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param array-key $head
     * @psalm-param array<array-key|array> $ahead
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupWithScalarHead(
        array $array,
        array $behind,
        mixed $head,
        array $ahead,
        mixed &$retval
    ): ?array {
        if (!array_key_exists($head, $array)) {
            return null;
        }

        $behind[] = $head;
        if (empty($ahead)) {
            /** @psalm-var mixed */
            $retval = $array[$head];

            return $behind;
        }

        if (!is_array($array[$head])) {
            return null;
        }

        return self::lookupRecursion($array[$head], $behind, $ahead, $retval);
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param array<array-key|array> $ahead
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupWithArrayHead(
        array $array,
        array $behind,
        array $head,
        array $ahead,
        mixed &$retval
    ): ?array {
        if (empty($head)) {
            return self::lookupRecursion($array, $behind, $ahead, $retval);
        }

        /** @psalm-var mixed*/
        foreach ($head as $entry) {
            if (null !== ($path = self::lookupWithMixedHead($array, $behind, $entry, $ahead, $retval))) {
                return $path;
            }
        }
        return null;
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param array<array-key|array> $ahead
     *
     * @psalm-return ?list<array-key>
     */
    private static function lookupWithMixedHead(
        array $array,
        array $behind,
        mixed $head,
        array $ahead,
        mixed &$retval
    ): ?array {
        if (is_string($head) || is_int($head)) {
            $head = (array) $head;
        }
        if (!is_array($head)) {
            return null;
        }
        // Psalm does not support recursive array types
        // (https://github.com/vimeo/psalm/issues/1892),
        // so we lie to it here (without costly runtime checks).
        /** @psalm-var array<array-key|array> */
        $lookup = [...$head, ...$ahead];
        return self::lookupRecursion($array, $behind, $lookup, $retval);
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Collections;

/**
 * Provides static methods for accessing inner elements of nested arrays.
 *
 * A *nested array* is simply an array of arrays. For example:
 *
 *      $array = [
 *          'foo' => [ 'baz' => 'FOO.BAZ' ],
 *          'bar' => [ 'baz' => 'BAR.BAZ' ],
 *      ];
 *
 * The inner elements can normally be accessed with multi-level indexing, for example:
 *
 *      $baz = $array['foo']['baz']
 *
 * The ``NestedArray`` allows accessing nested array elements using list of
 * keys. For example:
 *
 *      NestedArray::get($array, ['foo', 'bar'], $baz);
 *
 * retrieves the ``$array['foo']['bar']`` and assigns it to ``$baz``.
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TLookupArray array<array-key|array>
 */
final class NestedArray
{
    /**
     * Retrieve element of *$array* specified by *$path*.
     *
     * If *$array* has an element under *$path*, the method assigns its value
     * to *$retval* and returns ``true``. If not, it returns ``false``.
     *
     * @psalm-param array<array-key> $path
     *
     * @return bool ``true`` if *$array* has element specified by *$path*,
     *              ``false`` otherwise
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
     * Assigns *$value* to *$array* element specified by *$path*.
     *
     * Inner arrays are created if necessary.
     *
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
     * Unset *$array* element under *$path*.
     *
     * The (inner) array containing *$path* element may become empty due to
     * deletion. Such an emptied array is left as is, i.e it's not
     * automatically removed from the *$array*.
     *
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
     * Simple lookup algorithm, that searches for elements of nested array.
     *
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-return ?list<array-key>
     *
     * @return ?array returns the path to element found, or ``null`` if nothing
     *                was found
     */
    public static function lookup(array $array, array $lookup, mixed &$retval = null): ?array
    {
        return self::lookupRecursion($array, [], $lookup, $retval);
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param TLookupArray $ahead
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
        if (is_array($head)) {
            return self::lookupWithArrayHead($array, $behind, $head, $ahead, $retval);
        }

        return self::lookupWithScalarHead($array, $behind, $head, $ahead, $retval);
    }

    /**
     * @psalm-param list<array-key> $behind
     * @psalm-param array-key $head
     * @psalm-param TLookupArray $ahead
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
     * @psalm-param TLookupArray $ahead
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
     * @psalm-param TLookupArray $ahead
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
        /** @psalm-var TLookupArray */
        $lookup = [...$head, ...$ahead];

        return self::lookupRecursion($array, $behind, $lookup, $retval);
    }
}

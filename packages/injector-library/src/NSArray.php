<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * A convenient array for mapping PHP names (namespace/class/function names) to
 * arbitrary values. String keys are normalized before any operation. Leading
 * backslashes are removed and the key gets lower-cased. Integer keys are used
 * unchanged. This object may be used to implement simple containers with keys
 * representing namespace, class or function names.
 *
 * NOTE: Due to an old bug in PHP, ``isset()`` does not work as expected.
 * Instead of
 * ```
 * isset($array[$offset])
 * ```
 * one should use
 * ```
 * $array->offsetIsSet($offset);
 * ```
 *
 * See https://bugs.php.net/bug.php?id=41727 for more details.
 *
 * **Example**:
 *
 * ```
 * > $arr = new NSArray(["\\Foo\\Bar" => "FOO BAR", "\\Baz\\Gez" => "BAZ GEZ"]);
 *
 * > $arr->getArrayCopy();
 * = [
 *    "foo\bar" => "FOO BAR",
 *    "baz\gez" => "BAZ GEZ",
 *   ]
 *
 * > isset($arr["\\Foo\\Bar"]);
 * = true
 *
 * > $arr["FoO\\BAR"];
 * = "FOO BAR"
 * ```
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template KeyType of array-key
 * @psalm-template ValueType
 *
 * @template-extends \ArrayObject<KeyType,ValueType>
 */
final class NSArray extends \ArrayObject
{
    /**
     * @psalm-param iterable<KeyType,ValueType> $data
     */
    public function __construct(iterable|object $data = [], int $flags = 0)
    {
        if (!is_iterable($data)) {
            $data = (array)$data;
        }
        parent::__construct(iterator_to_array(self::normalizeKeys($data, $flags)));
    }

    public function offsetExists(mixed $offset): bool
    {
        return parent::offsetExists(self::normalizeKey($offset));
    }

    public function offsetGet(mixed $offset): mixed
    {
        return parent::offsetGet(self::normalizeKey($offset));
    }

    public function offsetIsSet(mixed $offset): bool
    {
        $offset = self::normalizeKey($offset);
        return parent::offsetExists($offset) && isset(parent::offsetGet($offset));
    }

    /**
     * @psalm-param KeyType $offset
     * @psalm-param ValueType $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        parent::offsetSet(self::normalizeKey($offset), $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        parent::offsetUnset(self::normalizeKey($offset));
    }

    public function exchangeArray(iterable|object $array): array
    {
        if (!is_iterable($array)) {
            $array = (array)$array;
        }
        return parent::exchangeArray(iterator_to_array(self::normalizeKeys($array)));
    }

    /**
     * @psalm-template K of array-key
     *
     * @psalm-param K $key
     *
     * @psalm-return (K is string ? lowercase-string : K)
     */
    private static function normalizeKey(mixed $key): mixed
    {
        if (!is_string($key)) {
            return $key;
        }

        return strtolower(ltrim($key, '\\'));
    }

    /**
     * @psalm-template K of array-key
     * @psalm-template T
     *
     * @psalm-param iterable<K,T> $iterable
     * @psalm-return \Generator<K,T>
     */
    private static function normalizeKeys(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $value) {
            yield self::normalizeKey($key) => $value;
        }
    }
}

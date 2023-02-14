<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * An abstract base class for arrays with key normalization. A subclass must
 * implemenent static method ``normalizeKey()``. All keys are passed through
 * this method before any operation (insertion, comparison, retrieval).
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
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template TKey
 * @psalm-template TValue
 *
 * @template-extends \ArrayObject<TKey,TValue>
 */
abstract class AbstractNormalizedKeyArray extends \ArrayObject
{
    /**
     * @psalm-param array<TKey,TValue>|object $data
     */
    final public function __construct(array|object $data = [], int $flags = 0)
    {
        if(is_iterable($data)) {
            $data = self::normalizeKeys($data);
        }
        parent::__construct($data, $flags);
    }

    /**
     * @psalm-param array-key $offset
     */
    final public function offsetExists(mixed $offset): bool
    {
        return parent::offsetExists(static::normalizeKey($offset));
    }

    /**
     * @psalm-param array-key $offset
     * @psalm-return TValue|null
     */
    final public function offsetGet(mixed $offset): mixed
    {
        return parent::offsetGet(static::normalizeKey($offset));
    }

    /**
     * @psalm-param array_key $offset
     */
    final public function offsetIsSet(mixed $offset): bool
    {
        $offset = static::normalizeKey($offset);

        return parent::offsetExists($offset) && null !== parent::offsetGet($offset);
    }

    /**
     * @psalm-param array-key $offset
     * @psalm-param TValue $value
     */
    final public function offsetSet(mixed $offset, mixed $value): void
    {
        parent::offsetSet(static::normalizeKey($offset), $value);
    }

    /**
     * @psalm-param array-key $offset
     */
    final public function offsetUnset(mixed $offset): void
    {
        parent::offsetUnset(static::normalizeKey($offset));
    }

    /**
     * @psalm-param array<TKey,TValue>|object $array
     */
    final public function exchangeArray(array|object $array): array
    {
        if(is_iterable($array)) {
            $array = self::normalizeKeys($array);
        }
        return parent::exchangeArray($array);
    }

    /**
     * @psalm-template K
     * @psalm-param K $key
     * @psalm-return (K is string ? string : K)
     */
    abstract protected static function normalizeKey(mixed $key): mixed;

    /**
     * @psalm-template K
     * @psalm-template V
     *
     * @psalm-param iterable<K,V> $array
     *
     * @psalm-return array<K,V>
     */
    private static function normalizeKeys(iterable $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[static::normalizeKey($key)] = $value;
        }
        return $result;
    }
}

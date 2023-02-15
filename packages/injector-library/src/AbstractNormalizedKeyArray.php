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
 * @psalm-template TNormKey of TKey
 *
 * @template-extends \ArrayObject<TKey,TValue>
 */
abstract class AbstractNormalizedKeyArray extends \ArrayObject
{
    final public function __construct(array|object $data = [], int $flags = 0)
    {
        if (is_iterable($data)) {
            parent::__construct(self::normalizeKeys($data), $flags);
        } else {
            parent::__construct($data, $flags);
        }
    }

    final public function offsetExists(mixed $offset): bool
    {
        return parent::offsetExists($this->normalizeKey($offset));
    }

    final public function offsetGet(mixed $offset): mixed
    {
        return parent::offsetGet($this->normalizeKey($offset));
    }

    final public function offsetIsSet(mixed $offset): bool
    {
        $offset = $this->normalizeKey($offset);

        return parent::offsetExists($offset) && null !== parent::offsetGet($offset);
    }

    final public function offsetSet(mixed $offset, mixed $value): void
    {
        parent::offsetSet($this->normalizeKey($offset), $value);
    }

    final public function offsetUnset(mixed $offset): void
    {
        parent::offsetUnset($this->normalizeKey($offset));
    }

    final public function exchangeArray(array|object $array): array
    {
        if (is_iterable($array)) {
            return parent::exchangeArray(self::normalizeKeys($array));
        }

        return parent::exchangeArray($array);
    }

    /**
     * @psalm-return TNormKey
     */
    abstract protected static function normalizeKey(mixed $key): mixed;

    /**
     * @psalm-template K
     * @psalm-template V
     *
     * @psalm-param iterable<K,V> $array
     *
     * @psalm-return array<TNormKey,V>
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

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
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template TKey
 * @psalm-template TValue
 *
 * @template-extends AbstractNormalizedKeyArray<TKey,TValue>
 */
final class NSArray extends AbstractNormalizedKeyArray
{
    /**
     * @psalm-template K
     *
     * @psalm-param K $key
     *
     * @psalm-return (K is string ? lowercase-string : K)
     */
    protected static function normalizeKey(mixed $key): mixed
    {
        if (!is_string($key)) {
            return $key;
        }

        return strtolower(ltrim($key, '\\'));
    }
}

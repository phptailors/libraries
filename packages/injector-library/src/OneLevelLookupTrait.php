<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait OneLevelLookupTrait
{
    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param null|TUnscopedArray $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    private static function oneLevelLookup(?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array || !array_key_exists($key, $array)) {
            return false;
        }

        /** @psalm-var TUnscopedArray[TKey] */
        $retval = $array[$key];

        return true;
    }
}

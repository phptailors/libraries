<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait TwoLevelLookupTrait
{
    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param TUnscopedArray $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    abstract private static function oneLevelLookup(array $array, string $key, mixed &$retval = null): bool;

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,TUnscopedArray> $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    private static function twoLevelLookup(array $skeys, ?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array) {
            return false;
        }

        foreach ($skeys as $skey) {
            if (self::oneLevelLookup($array[$skey] ?? null, $key, $retval)) {
                return true;
            }
        }

        return false;
    }
}

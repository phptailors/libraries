<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait ThreeLevelLookupTrait
{
    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array<string> $skeys
     * @psalm-param array<string,TUnscopedArray> $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    abstract private static function twoLevelLookup(
        array $skeys,
        array $array,
        string $key,
        mixed &$retval = null
    ): bool;

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param list{string,string|array<string>} $skeys
     * @psalm-param null|array<string,array<string,TUnscopedArray>> $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    private static function threeLevelLookup(array $skeys, ?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array) {
            return false;
        }

        return self::twoLevelLookup((array) $skeys[1], $array[$skeys[0]] ?? null, $key, $retval);
    }
}

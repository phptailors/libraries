<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait ThreeLevelLookupTrait
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array<string> $skeys
     * @psalm-param array<string,array<TKey,TVal>> $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    abstract private static function twoLevelArrayLookup(
        array $skeys,
        array $array,
        string $key,
        mixed &$retval = null
    ): bool;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,class-string-map<T,T>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    abstract private static function twoLevelInstanceMapLookup(array $skeys, ?array $array, string $class): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,class-string-map<T,FactoryInterface<T>>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    abstract private static function twoLevelFactoryMapLookup(
        array $skeys,
        ?array $array,
        string $class
    ): ?FactoryInterface;

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of string
     *
     * @psalm-param list{string,string|array<string>} $skeys
     * @psalm-param null|array<string,array<string,array<TKey,TVal>>> $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    private static function threeLevelArrayLookup(array $skeys, ?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array) {
            return false;
        }

        return self::twoLevelArrayLookup((array) $skeys[1], $array[$skeys[0]] ?? null, $key, $retval);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param list{string,string|array<string>} $skeys
     * @psalm-param null|array<string,array<string,class-string-map<T,T>>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    private static function threeLevelInstanceMapLookup(array $skeys, ?array $array, string $class): ?object
    {
        if (null === $array) {
            return null;
        }

        return self::twoLevelInstanceMapLookup((array) $skeys[1], $array[$skeys[0]] ?? null, $class);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param list{string,string|array<string>} $skeys
     * @psalm-param null|array<string,array<string,class-string-map<T,FactoryInterface<T>>>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    private static function threeLevelFactoryMapLookup(array $skeys, ?array $array, string $class): ?FactoryInterface
    {
        if (null === $array) {
            return null;
        }

        return self::twoLevelFactoryMapLookup((array) $skeys[1], $array[$skeys[0]] ?? null, $class);
    }
}

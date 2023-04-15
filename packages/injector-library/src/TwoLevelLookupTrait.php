<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait TwoLevelLookupTrait
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array<TKey,TVal> $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    abstract private static function oneLevelArrayLookup(array $array, string $key, mixed &$retval = null): bool;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param null|class-string-map<T,T> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    abstract private static function oneLevelInstanceMapLookup(?array $array, string $class): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param null|class-string-map<T,FactoryInterface<T>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    abstract private static function oneLevelFactoryMapLookup(?array $array, string $class): ?FactoryInterface;

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,array<TKey,TVal>> $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    private static function twoLevelArrayLookup(array $skeys, ?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array) {
            return false;
        }

        foreach ($skeys as $skey) {
            if (self::oneLevelArrayLookup($array[$skey] ?? null, $key, $retval)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,class-string-map<T,T>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    private static function twoLevelInstanceMapLookup(array $skeys, ?array $array, string $class): ?object
    {
        if (null === $array) {
            return null;
        }

        foreach ($skeys as $skey) {
            if (null !== ($obj = self::oneLevelInstanceMapLookup($array[$skey] ?? null, $class))) {
                return $obj;
            }
        }

        return null;
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array<string> $skeys
     * @psalm-param null|array<string,class-string-map<T,FactoryInterface<T>>> $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    private static function twoLevelFactoryMapLookup(array $skeys, ?array $array, string $class): ?FactoryInterface
    {
        if (null === $array) {
            return null;
        }

        foreach ($skeys as $skey) {
            if (null !== ($obj = self::oneLevelFactoryMapLookup($array[$skey] ?? null, $class))) {
                return $obj;
            }
        }

        return null;
    }
}

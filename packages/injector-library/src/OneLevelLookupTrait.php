<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

trait OneLevelLookupTrait
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param null|array<TKey,TVal> $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    private static function oneLevelArrayLookup(?array $array, string $key, mixed &$retval = null): bool
    {
        if (null === $array || !array_key_exists($key, $array)) {
            return false;
        }

        $retval = $array[$key];

        return true;
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param null|class-string-map<T,T> $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    private static function oneLevelInstanceMapLookup(?array $array, string $class): ?object
    {
        if (null === $array || !array_key_exists($class, $array)) {
            return null;
        }

        return $array[$class];
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param null|class-string-map<T,FactoryInterface<T>> $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    private static function oneLevelFactoryMapLookup(?array $array, string $class): ?FactoryInterface
    {
        if (null === $array || !array_key_exists($class, $array)) {
            return null;
        }

        return $array[$class];
    }
}

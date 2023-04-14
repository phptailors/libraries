<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractOneLevelScopeLookupBase
{
    use OneLevelLookupTrait;

    /**
     * @psalm-return 'global'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return null
     */
    final public function getScopeLookup(): mixed
    {
        return null;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{global?: array<TKey,TVal>, ...} $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    final public function lookupScopedArray(array $array, string $key, mixed &$retval = null): bool
    {
        return self::oneLevelArrayLookup($array[$this->getScopeType()] ?? null, $key, $retval);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{global?: class-string-map<T,T>, ...} $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    public function lookupScopedInstanceMap(array $array, string $class): ?object
    {
        return self::oneLevelInstanceMapLookup($array[$this->getScopeType()] ?? null, $class);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{global?: class-string-map<T,FactoryInterface<T>>, ...} $array
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function lookupScopedFactoryMap(array $array, string $class): ?FactoryInterface
    {
        return self::oneLevelFactoryMapLookup($array[$this->getScopeType()] ?? null, $class);
    }
}

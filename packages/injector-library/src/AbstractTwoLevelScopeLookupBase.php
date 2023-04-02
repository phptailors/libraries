<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractTwoLevelScopeLookupBase
{
    use OneLevelLookupTrait;
    use TwoLevelLookupTrait;

    /**
     * @psalm-var string|array<string>
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param string|array<string> $scopeLookup
     */
    final public function __construct(string|array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    /**
     * @psalm-return 'class'|'function'|'namespace'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return string|array<string>
     */
    final public function getScopeLookup(): string|array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{
     *      class?: array<string,array<TKey,TVal>>,
     *      namespace?: array<string,array<TKey,TVal>>,
     *      function?: array<string,array<TKey,TVal>>,
     *      ...
     * } $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    final public function lookupScopedArray(array $array, string $key, mixed &$retval = null): bool
    {
        $scopeLookup = (array) $this->scopeLookup;
        /** @psalm-suppress PossiblyUndefinedArrayOffset */ // psalm bug...
        return self::twoLevelArrayLookup($scopeLookup, $array[$this->getScopeType()] ?? null, $key, $retval);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{
     *      class?:     array<string,class-string-map<T,T>>,
     *      function?:  array<string,class-string-map<T,T>>,
     *      namespace?: array<string,class-string-map<T,T>>,
     *      ...
     * } $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    final public function lookupScopedInstanceMap(array $array, string $class): ?object
    {
        $scopeLookup = (array) $this->scopeLookup;
        /** @psalm-suppress PossiblyUndefinedArrayOffset */ // psalm bug...
        return self::twoLevelInstanceMapLookup($scopeLookup, $array[$this->getScopeType()] ?? null, $class);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{
     *      class?:     array<string,class-string-map<T,FactoryInterface<T>>>,
     *      function?:  array<string,class-string-map<T,FactoryInterface<T>>>,
     *      namespace?: array<string,class-string-map<T,FactoryInterface<T>>>,
     *      ...
     * } $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    final public function lookupScopedFactoryMap(array $array, string $class): ?FactoryInterface
    {
        $scopeLookup = (array) $this->scopeLookup;
        /** @psalm-suppress PossiblyUndefinedArrayOffset */ // psalm bug...
        return self::twoLevelFactoryMapLookup($scopeLookup, $array[$this->getScopeType()] ?? null, $class);
    }
}

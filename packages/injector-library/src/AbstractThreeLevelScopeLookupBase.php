<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractThreeLevelScopeLookupBase
{
    use OneLevelLookupTrait;
    use TwoLevelLookupTrait;
    use ThreeLevelLookupTrait;

    /**
     * @psalm-var list{string,string|array<string>}
     */
    private array $scopeLookup;

    /**
     * @psalm-param list{string,string|array<string>} $scopeLookup
     */
    final public function __construct(array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    /**
     * @psalm-return 'method'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return list{string,string|array<string>}
     */
    final public function getScopeLookup(): array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{method?: array<string,array<string,array<TKey,TVal>>>, ...} $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    final public function lookupScopedArray(array $array, string $key, mixed &$retval = null): bool
    {
        return self::threeLevelArrayLookup($this->scopeLookup, $array[$this->getScopeType()] ?? null, $key, $retval);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{method?: array<string,array<string,class-string-map<T,T>>>, ...} $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    final public function lookupScopedInstanceMap(array $array, string $class): ?object
    {
        return self::threeLevelInstanceMapLookup($this->scopeLookup, $array[$this->getScopeType()] ?? null, $class);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param array{method?: array<string,array<string,class-string-map<T,FactoryInterface<T>>>>, ...} $array
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    final public function lookupScopedFactoryMap(array $array, string $class): ?FactoryInterface
    {
        return self::threeLevelFactoryMapLookup($this->scopeLookup, $array[$this->getScopeType()] ?? null, $class);
    }
}

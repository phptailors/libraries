<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeType of string
 *
 * @template-extends ScopeLookupInterface<TScopeType>
 */
interface TwoLevelScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return string|array<string>
     */
    public function getScopeLookup(): string|array;

    /**
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{
     *      class?:     array<string,array<TKey,TVal>>,
     *      function?:  array<string,array<TKey,TVal>>,
     *      namespace?: array<string,array<TKey,TVal>>,
     *      ...
     * } $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    public function lookupScopedArray(array $array, string $key, mixed &$retval = null): bool;

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
    public function lookupScopedInstanceMap(array $array, string $class): ?object;

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
    public function lookupScopedFactoryMap(array $array, string $class): ?FactoryInterface;
}

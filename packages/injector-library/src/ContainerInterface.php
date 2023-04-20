<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TLookupArray array<array-key|array>
 * @psalm-type TScopeType "class"|"function"|"global"|"method"|"namespace"
 * @psalm-type TScopePath list{0: TScopeType, 1?: string, 2?: string}
 * @psalm-type TItemPath list{0: TScopeType, 1: string, 2?: string, 3?: string}
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 * @psalm-type TInstances array{
 *      class?:     array<string,class-string-map<T,T>>,
 *      namespace?: array<string,class-string-map<T,T>>,
 *      function?:  array<string,class-string-map<T,T>>,
 *      method?:    array<string,array<string, class-string-map<T,T>>>,
 *      global?:    class-string-map<T,T>
 * }
 * @psalm-type TFactories array{
 *      class?:     array<string,class-string-map<T,FactoryInterface<T>>>,
 *      namespace?: array<string,class-string-map<T,FactoryInterface<T>>>,
 *      function?:  array<string,class-string-map<T,FactoryInterface<T>>>,
 *      method?:    array<string,array<string, class-string-map<T,FactoryInterface<T>>>>,
 *      global?:    class-string-map<T,FactoryInterface<T>>
 * }
 */
interface ContainerInterface
{
    /**
     * @pslam-return TAliases
     */
    public function getAliases(): array;

    /**
     * @pslam-return TInstances
     */
    public function getInstances(): array;

    /**
     * @pslam-return TFactories
     */
    public function getFactories(): array;

    /**
     * @psalm-param TScopePath $scope
     */
    public function setAlias(string $abstract, string $alias, array $scope = null): void;

    /**
     * @psalm-param TScopePath $scope
     */
    public function getAlias(string $alias, array $scope = null): ?string;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param TObj $object
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     */
    public function setInstance(object $object, string $class, array $scope = null): void;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     *
     * @psalm-return ?TObj
     */
    public function getInstance(string $class, array $scope = null): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param FactoryInterface<TObj> $factory
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     */
    public function setFactory(FactoryInterface $factory, string $class, array $scope = null): void;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function getFactory(string $class, array $scope = null): ?FactoryInterface;

    /**
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-param-out ?string $abstract
     *
     * @psalm-return ?TItemPath
     */
    public function lookupAlias(string $alias, array $lookup, mixed &$abstract = null): ?array;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-param-out ?TObj $instance
     *
     * @psalm-return ?TItemPath
     */
    public function lookupInstance(string $class, array $lookup, mixed &$instance = null): ?array;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-param-out ?FactoryInterface<TObj> $factory
     *
     * @psalm-return ?TItemPath
     */
    public function lookupFactory(string $class, array $lookup, mixed &$factory = null): ?array;
}

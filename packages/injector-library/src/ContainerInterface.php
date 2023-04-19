<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TScopeType ("class"|"function"|"method"|"namespace"|"global")
 * @psalm-type TScopePath list{0: TScopeType, 1?: string, 2?: string, 3?: string}
 */
interface ContainerInterface
{
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
     * @psalm-param class-string<TObj> $class
     * @psalm-param TObj $object
     * @psalm-param TScopePath $scope
     */
    public function setInstance(string $class, object $object, array $scope = null): void;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     * @psalm-return ?TObj
     */
    public function getInstance(string $class, array $scope = null): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param FactoryInterface<TObj> $factory
     * @psalm-param TScopePath $scope
     */
    public function setFactory(string $class, FactoryInterface $factory, array $scope = null): void;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function getFactory(string $class, array $scope = null): ?FactoryInterface;

    public function lookupAlias(string $key, ContextInterface $context): ?string;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    public function lookupInstance(string $class, ContextInterface $context): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function lookupFactory(string $class, ContextInterface $context): ?FactoryInterface;
}

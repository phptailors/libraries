<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TLookupArray from ContainerInterface
 * @psalm-import-type TScopeType from ContainerInterface
 * @psalm-import-type TScopePath from ContainerInterface
 * @psalm-import-type TItemPath from ContainerInterface
 * @psalm-import-type TAliases from ContainerInterface
 * @psalm-import-type TInstances from ContainerInterface
 * @psalm-import-type TFactories from ContainerInterface
 */
final class Container implements ContainerInterface
{
    /**
     * @psalm-readonly
     *
     * @psalm-var array<TScopeType,int>
     */
    private static array $scopeDepth = [
        'class'     => 2,
        'function'  => 2,
        'method'    => 3,
        'namespace' => 2,
        'global'    => 1,
    ];

    /**
     * @psalm-var TAliases
     */
    private array $aliases;

    /**
     * @psalm-var TInstances
     */
    private array $instances;

    /**
     * @psalm-var TFactories
     */
    private array $factories;

    /**
     * @psalm-param TAliases $aliases
     * @psalm-param TInstances $instances
     * @psalm-param TFactories $factories
     */
    public function __construct(array $aliases = [], array $instances = [], array $factories = [])
    {
        $this->aliases = $aliases;
        $this->instances = $instances;
        $this->factories = $factories;
    }

    /**
     * @psalm-return TAliases
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @psalm-return TInstances
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * @psalm-return TFactories
     */
    public function getFactories(): array
    {
        return $this->factories;
    }

    /**
     * @psalm-param TScopePath $scope
     */
    public function setAlias(string $abstract, string $alias, array $scope = null): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        self::arraySet($this->aliases, $alias, $abstract, $scope);
    }

    /**
     * @psalm-param TScopePath $scope
     */
    public function getAlias(string $alias, array $scope = null): ?string
    {
        /** @psalm-var ?string */
        return self::arrayGet($this->aliases, $alias, $scope);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param TObj $object
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     */
    public function setInstance(object $object, string $class, array $scope = null): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        self::arraySet($this->instances, $class, $object, $scope);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     *
     * @psalm-return ?TObj
     */
    public function getInstance(string $class, array $scope = null): ?object
    {
        /** @psalm-var ?TObj */
        return self::arrayGet($this->instances, $class, $scope);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param FactoryInterface<TObj> $factory
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     */
    public function setFactory(FactoryInterface $factory, string $class, array $scope = null): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        self::arraySet($this->factories, $class, $factory, $scope);
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     * @psalm-param TScopePath $scope
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function getFactory(string $class, array $scope = null): ?FactoryInterface
    {
        /** @psalm-var ?FactoryInterface<TObj> */
        return self::arrayGet($this->factories, $class, $scope);
    }

    /**
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-param-out ?string $abstract
     *
     * @psalm-return ?TItemPath
     */
    public function lookupAlias(string $alias, array $lookup, mixed &$abstract = null): ?array
    {
        /** @psalm-var ?TItemPath */
        return self::arrayLookup($this->aliases, [$lookup, $alias], $abstract);
    }

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
    public function lookupInstance(string $class, array $lookup, mixed &$instance = null): ?array
    {
        /** @psalm-var ?TItemPath */
        return self::arrayLookup($this->instances, [$lookup, $class], $instance);
    }

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
    public function lookupFactory(string $class, array $lookup, mixed &$factory = null): ?array
    {
        /** @psalm-var ?TItemPath */
        return self::arrayLookup($this->factories, [$lookup, $class], $factory);
    }

    /**
     * @psalm-param TScopePath|null $scope
     */
    private static function arrayGet(array $array, string $key, ?array $scope): mixed
    {
        $scope ??= ['global'];
        $scopeType = $scope[0];
        if (count($scope) !== (self::$scopeDepth[$scopeType] ?? null)) {
            return null; // FIXME: throw an exception instead?
        }
        if (!NestedArray::get($array, [...$scope, $key], $result)) {
            return null;
        }

        return $result;
    }

    /**
     * @psalm-param TScopePath|null $scope
     */
    private static function arraySet(array &$array, string $key, mixed $value, ?array $scope): void
    {
        $scope ??= ['global'];
        $scopeType = $scope[0];
        if (count($scope) !== (self::$scopeDepth[$scopeType] ?? null)) {
            return; // FIXME: throw an exception instead?
        }

        NestedArray::set($array, [...$scope, $key], $value);
    }

    /**
     * @psalm-param non-empty-array<array-key|array> $lookup
     */
    private static function arrayLookup(array $array, array $lookup, mixed &$retval): ?array
    {
        /** @psalm-var ?non-empty-list<string> */
        $path = NestedArray::lookup($array, $lookup, $temp);
        if (null === $path) {
            return null;
        }

        $scopeType = $path[0];
        if ((count($path) - 1) !== (self::$scopeDepth[$scopeType] ?? null)) {
            return null;
        }

        /** @psalm-var mixed */
        $retval = $temp;

        return $path;
    }
}

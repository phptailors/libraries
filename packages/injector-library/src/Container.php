<?php

namespace Tailors\Lib\Injector;


/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TUnscopedAliases array<string,string>
 * @psalm-type TUnscopedInstances class-string-map<T,T>
 * @psalm-type TUnscopedFactories class-string-map<T,FactoryInterface<T>>
 *
 * @psalm-type TScopedAliases array{
 *      ClassScope?: array<string, TUnscopedAliases>,
 *      NamespaceScope?: array<string, TUnscopedAliases>,
 *      FunctionScope?: array<string, TUnscopedAliases>,
 *      MethodScope?: array<string, array<string, TUnscopedAliases>>,
 *      GlobalScope?: TUnscopedAliases
 * }
 *
 * @psalm-type TScopedInstances array{
 *      ClassScope?: array<string, TUnscopedInstances>,
 *      NamespaceScope?: array<string, TUnscopedInstances>,
 *      FunctionScope?: array<string, TUnscopedInstances>,
 *      MethodScope?: array<string, array<string, TUnscopedInstances>>,
 *      GlobalScope?: TUnscopedInstances
 * }
 *
 * @psalm-type TScopedFactories array{
 *      ClassScope?: array<string, TUnscopedFactories>,
 *      NamespaceScope?: array<string, TUnscopedFactories>,
 *      FunctionScope?: array<string, TUnscopedFactories>,
 *      MethodScope?: array<string, array<string, TUnscopedFactories>>,
 *      GlobalScope?: TUnscopedFactories
 * }
 */
class Container
{
    /**
     * @psalm-var TScopedAliases
     */
    private array $aliases;

    /**
     * @psalm-var TScopedInstances
     */
    private array $instances;

    /**
     * @psalm-var TScopedFactories
     */
    private array $factories;


    /**
     * @psalm-param TScopedAliases $aliases
     * @psalm-param TScopedInstances $instances
     * @psalm-param TScopedFactories $factories
     */
    public function __construct(array $aliases, array $instances, array $factories)
    {
        $this->aliases = $aliases;
        $this->instances = $instances;
        $this->factories = $factories;
    }

    /**
     * @psalm-return TScopedAliases
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @psalm-return TScopedInstances
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * @psalm-return TScopedFactories
     */
    public function getFactories(): array
    {
        return $this->factories;
    }

    public function hasAlias(string $key, string $scope = 'GlobalScope', string $scopeKey = ''): bool
    {
        if (!isset($this->aliases[$scope])) {
            return false;
        }

        if ($scope === 'GlobalScope') {
            return isset($this->aliases[$scope][$key]);
        }

        return isset($this->aliases[$scope]
    }
}

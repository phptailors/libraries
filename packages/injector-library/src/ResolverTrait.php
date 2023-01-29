<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * Implementation of the ResolverInterface.
 *
 * @psalm-import-type AliasesArray from ResolverContainerInterface
 * @psalm-import-type InstancesArray from ResolverContainerInterface
 * @psalm-import-type BindingsArray from ResolverContainerInterface
 * @psalm-import-type ContextualBindingsArray from ResolverContainerInterface
 */
trait ResolverTrait
{
    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     *                           Class, interface or alias name to be resolved. If parameters are
     *                           required for the resolution (for example if a non-trivial
     *                           constructor has to be called to create requested instance), they'll
     *                           be resolved recursivelly. To prevent automatic resolution,
     *                           parameters may be provided via **$parameters**.
     * @param array  $parameters
     *                           If provided, will be passed to constructor of resolve $abstract or
     *                           to callable. If missing, the required parameters will be
     *                           automatically resolved using container.
     *
     * @return mixed
     *
     * @throws CircularDependencyException
     */
    public function resolve(string $abstract, array $parameters = null): mixed
    {
        // TODO: implement
        return $this->resolveAlias($abstract);
    }

    /**
     * Determine if a given string is an alias.
     */
    public function isAlias(string $key): bool
    {
        $aliases = $this->getAliases();

        return isset($aliases[$key]);
    }

    /**
     * Returns alias target (string) if **$key** is an alias, or **$key** if it's not.
     *
     * @throws CircularDependencyException
     */
    public function resolveAlias(string $key): string
    {
        return self::walkAliases($this->getAliases(), $key);
    }

    /**
     * Determine if a given string points to a shared instance.
     */
    public function isShared(string $abstract): bool
    {
        $instances = $this->getInstances();
        $bindings = $this->getBindings();

        return isset($instances[$abstract]) || isset($bindings[$abstract]) && $bindings[$abstract]->shared();
    }

    /**
     * @psalm-return AliasesArray
     */
    abstract public function getAliases(): array;

    /**
     * @psalm-return InstancesArray
     */
    abstract public function getInstances(): array;

    /**
     * @psalm-return BindingsArray
     */
    abstract public function getBindings(): array;

    /**
     * @psalm-return ContextualBindingsArray
     */
    abstract public function getContextualBindings(): array;

    /**
     * @throws CircularDependencyException
     *
     * @psalm-param AliasesArray $aliases
     * @psalm-param array<string,bool> $seen;
     */
    protected static function walkAliases(array $aliases, string $key, array &$seen = []): string
    {
        if (null === ($alias = $aliases[$key] ?? null)) {
            return $key;
        }

        $seen[$key] = true;
        if (true === ($seen[$alias] ?? false)) {
            $message = sprintf(
                'Error resolving alias: circular dependency %s => %s',
                var_export($key, true),
                var_export($alias, true)
            );

            throw new CircularDependencyException($message);
        }

        return self::walkAliases($aliases, $alias, $seen);
    }
}

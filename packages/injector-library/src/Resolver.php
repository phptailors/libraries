<?php

namespace Tailors\Lib\Injector;

final class Resolver implements ResolverInterface
{
    private RegistryInterface $registry;

    public function __construct(RetistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Resolve the dependency specified by **$abstract**.
     *
     * @param string $abstract A class or interface name, or an alias to be resolved
     */
    public function resolve(string $abstract): mixed
    {
    }

    /**
     * Resolve object specified by **$type**.
     *
     * @param string $type A class or interface name of the object to be resolved.
     *
     * @psalm-template C
     *
     * @psalm-param class-string<C> $type
     *
     * @psalm-return C
     */
    public function resolveObject(string $type): object
    {
    }
}

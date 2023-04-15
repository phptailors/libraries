<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

interface ResolverInterface
{
    /**
     * Resolve the dependency specified by **$abstract**.
     *
     * @param string $abstract A class or interface name, or an alias to be resolved
     */
    public function resolve(string $abstract): mixed;

    /**
     * Resolve object specified by **$type**.
     *
     * @param string $type a class or interface name of the object to be resolved
     *
     * @psalm-template TObj
     *
     * @psalm-param class-string<TObj> $type
     *
     * @psalm-return TObj
     */
    public function resolveObject(string $type): object;
}

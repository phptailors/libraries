<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

interface ResolverInterface
{
    /**
     * Resolve the dependency specified by **$abstract**.
     *
     * @param string $abstract
     *                           A class or interface name, or an alias to be
     *                           resolved
     * @param array  $parameters
     *                           Optional parameters passed to user-defined bind
     *                           callback
     *
     * @return mixed
     *
     * @psalm-param list<mixed> $parameters
     */
    public function resolve(string $abstract, array $parameters = null): mixed;
}

<?php

declare(strict_types=1);

namespace Tailors\Lib\Injector;

interface ResolverInterface
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
     */
    public function resolve(string $abstract, array $parameters = null): mixed;
}

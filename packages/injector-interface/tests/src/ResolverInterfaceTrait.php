<?php

declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait ResolverInterfaceTrait
{
    public mixed $resolve;

    public function resolve(string $abstract, array $parameters = null): mixed
    {
        return $this->resolve;
    }
}

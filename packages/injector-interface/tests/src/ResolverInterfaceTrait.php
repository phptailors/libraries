<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatiblity promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait ResolverInterfaceTrait
{
    public mixed $resolve;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed
    {
        return $this->resolve;
    }
}

// vim: syntax=php sw=4 ts=4 et:

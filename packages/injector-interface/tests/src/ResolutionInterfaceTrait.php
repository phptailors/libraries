<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatiblity promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait ResolutionInterfaceTrait
{
    public mixed $resolve;

    public mixed $resolveClass;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed
    {
        return $this->resolve;
    }

    /**
     * @psalm-template T of object
     *
     * @psalm-param class-string<T> $class
     *
     * @psalm-return T
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws TypeExceptionInterface
     *
     * @psalm-suppress MixedInferredReturnType
     */
    public function resolveClass(string $class): object
    {
        return $this->resolveClass;
    }
}

// vim: syntax=php sw=4 ts=4 et:

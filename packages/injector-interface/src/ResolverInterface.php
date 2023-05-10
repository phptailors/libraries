<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
interface ResolverInterface
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed;

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
     */
    public function resolveClass(string $class): object;
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * An abstraction of container items that may be resolved to an instance
 * (service) using [ResolverInterface](ResolverInterface.html).
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ResolvableInterface
{
    /**
     * Resolve this item using *$resolver*.
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(ResolverInterface $resolver): mixed;
}

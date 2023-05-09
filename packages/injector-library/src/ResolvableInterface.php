<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ResolvableInterface
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(ResolverInterface $resolver): mixed;
}
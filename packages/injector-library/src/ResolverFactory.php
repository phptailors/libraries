<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ResolverFactory implements ResolverFactoryInterface
{
    public function getResolver(ItemContainerInterface $container): ResolverInterface
    {
        return new Resolver($container);
    }
}

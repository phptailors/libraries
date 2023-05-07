<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class ResolverFactory implements ResolverFactoryInterface
{
    public function getResolver(ContainerInterface $container): ResolverInterface
    {
        return new Resolver($container);
    }
}

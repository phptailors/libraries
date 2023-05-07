<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
interface ResolverFactoryInterface
{
    public function getResolver(ContainerInterface $container): ResolverInterface;
}

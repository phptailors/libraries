<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ResolverFactory
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ResolverFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResolverFactoryInterface(): void
    {
        $this->assertImplementsInterface(ResolverFactoryInterface::class, ResolverFactory::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetResolver(): void
    {
        $container = $this->createStub(ContainerInterface::class);

        $factory = new ResolverFactory();

        $resolver = $factory->getResolver($container);

        $this->assertInstanceOf(Resolver::class, $resolver);

        $this->assertSame($container, $resolver->getContainer());
    }
}

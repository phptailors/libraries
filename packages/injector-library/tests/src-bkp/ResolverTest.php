<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\InjectorContainerInterface;
use Tailors\Lib\Injector\Resolver;
use Tailors\Lib\Injector\ResolverInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Resolver
 *
 * @internal
 */
final class ResolverTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResolverInterface(): void
    {
        $this->assertImplementsInterface(ResolverInterface::class, Resolver::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(): void
    {
        $container = $this->createStub(InjectorContainerInterface::class);
        $resolver = new Resolver($container);
        $this->assertSame($container, $resolver->getContainer());
    }

    public function testResolveType(): void
    {
    }
}

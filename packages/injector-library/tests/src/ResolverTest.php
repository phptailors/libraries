<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Resolver
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
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
    public function testImplementsContextAwareResolverInterface(): void
    {
        $this->assertImplementsInterface(ContextAwareResolverInterface::class, Resolver::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $container = $this->createStub(ItemContainerInterface::class);

        $resolver = new Resolver($container);

        $this->assertSame($container, $resolver->getContainer());
        $this->assertSame([], $resolver->getBacktrace());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveCallsContainerGetItemResolve(): void
    {
        $container = $this->getMockBuilder(ItemContainerInterface::class)
            ->getMock()
        ;

        $fooItem = $this->getMockBuilder(ItemInterface::class)
            ->getMock()
        ;

        $resolver = new Resolver($container);

        $container->expects($this->once())
            ->method('getItem')
            ->with('foo')
            ->willReturn($fooItem)
        ;

        $fooItem->expects($this->once())
            ->method('resolve')
            ->with($resolver)
            ->willReturn('FOO')
        ;

        $this->assertSame('FOO', $resolver->resolve('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveThrowsCircularDependencyException(): void
    {
        $container = $this->getMockBuilder(ItemContainerInterface::class)
            ->getMock()
        ;

        $fooItem = $this->getMockBuilder(ItemInterface::class)
            ->getMock()
        ;

        $barItem = $this->getMockBuilder(ItemInterface::class)
            ->getMock()
        ;

        $resolver = new Resolver($container);

        $fooItem->expects($this->once())
            ->method('resolve')
            ->with($resolver)
            ->will($this->returnCallback(function (ResolverInterface $resolver): mixed {
                return $resolver->resolve('bar');
            }))
        ;

        $barItem->expects($this->once())
            ->method('resolve')
            ->with($resolver)
            ->will($this->returnCallback(function (ResolverInterface $resolver): mixed {
                return $resolver->resolve('foo');
            }))
        ;

        $container->expects($this->any())
            ->method('getItem')
            ->will($this->returnCallback(function (string $id) use ($fooItem, $barItem): mixed {
                switch ($id) {
                    case 'foo':
                        return $fooItem;

                    case 'bar':
                        return $barItem;

                    default:
                        throw new NotFoundException('not found');
                }
            }))
        ;

        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage('circular dependency: \'foo\' -> \'bar\' -> \'foo\'');

        $resolver->resolve('foo');
    }
}

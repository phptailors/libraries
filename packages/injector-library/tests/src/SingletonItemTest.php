<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\SingletonItem
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class SingletonItemTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsSingletonItemInterface(): void
    {
        $this->assertImplementsInterface(ItemInterface::class, SingletonItem::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(): void
    {
        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => (new \stdClass());

        $singletonItem = new SingletonItem($callback);

        $this->assertSame($callback, $singletonItem->getCallback());
        $this->assertNull($singletonItem->getInstance());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolve(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();

        $resolver->expects($this->never())
            ->method('resolve')
        ;

        /** @psalm-suppress UnusedClosureParam */
        $singleton = new SingletonItem(fn (ResolverInterface $resolver): \stdClass => (new \stdClass()));

        $instance = $singleton->resolve($resolver);
        $this->assertInstanceOf(\stdClass::class, $instance);
        $this->assertSame($instance, $singleton->resolve($resolver));
    }
}

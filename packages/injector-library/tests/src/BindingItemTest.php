<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\BindingItem
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class BindingItemTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsBindingItemInterface(): void
    {
        $this->assertImplementsInterface(ItemInterface::class, BindingItem::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(): void
    {
        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => (new \stdClass());

        $bindingItem = new BindingItem($callback);

        $this->assertSame($callback, $bindingItem->getCallback());
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
        $binding = new BindingItem(fn (ResolverInterface $resolver): \stdClass => (new \stdClass()));

        $instance = $binding->resolve($resolver);
        $this->assertInstanceOf(\stdClass::class, $instance);
        $this->assertNotSame($instance, $binding->resolve($resolver));
    }
}

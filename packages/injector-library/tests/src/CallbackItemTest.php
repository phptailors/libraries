<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\CallbackItem
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class CallbackItemTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsCallbackItemInterface(): void
    {
        $this->assertImplementsInterface(ItemInterface::class, CallbackItem::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(): void
    {
        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => (new \stdClass());

        $callbackItem = new CallbackItem($callback);

        $this->assertSame($callback, $callbackItem->getCallback());
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
        $callbackItem = new CallbackItem(fn (ResolverInterface $resolver): \stdClass => (new \stdClass()));

        $instance = $callbackItem->resolve($resolver);
        $this->assertInstanceOf(\stdClass::class, $instance);
        $this->assertNotSame($instance, $callbackItem->resolve($resolver));
    }
}

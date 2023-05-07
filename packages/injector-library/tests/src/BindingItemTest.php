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
    public function testResolve(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();

        $resolver->expects($this->never())
            ->method('resolve')
        ;

        /** @psalm-suppress UnusedClosureParam */
        $singleton = new BindingItem(fn (ResolverInterface $resolver): \stdClass => (new \stdClass()));

        $instance = $singleton->resolve($resolver);
        $this->assertInstanceOf(\stdClass::class, $instance);
        $this->assertNotSame($instance, $singleton->resolve($resolver));
    }
}

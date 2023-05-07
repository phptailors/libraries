<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\InstanceItem
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class InstanceItemTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInstanceItemInterface(): void
    {
        $this->assertImplementsInterface(ItemInterface::class, InstanceItem::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(): void
    {
        $instance = new \stdClass();

        $instanceItem = new InstanceItem($instance);

        $this->assertSame($instance, $instanceItem->getInstance());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolve(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();

        $instance = new \stdClass();

        $resolver->expects($this->never())
            ->method('resolve')
        ;

        $alias = new InstanceItem($instance);

        $this->assertSame($instance, $alias->resolve($resolver));
    }
}

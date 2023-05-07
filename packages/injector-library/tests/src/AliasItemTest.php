<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AliasItem
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class AliasItemTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsAliasItemInterface(): void
    {
        $this->assertImplementsInterface(ItemInterface::class, AliasItem::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $aliasItem = new AliasItem('target');

        $this->assertSame('target', $aliasItem->getTarget());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolve(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)->getMock();
        $target = $this->getMockBuilder(ItemInterface::class)->getMock();

        $resolver->expects($this->once())
            ->method('resolve')
            ->with('target')
            ->willReturn($target)
        ;

        $alias = new AliasItem('target');

        $this->assertSame($target, $alias->resolve($resolver));
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * Class using AliasesWrapperTrait for testing purposes.
 */
final class AliasesWrapper5EMA9 implements AliasesInterface, AliasesWrapperInterface
{
    use AliasesWrapperTrait;

    private AliasesInterface $aliases;

    public function __construct(AliasesInterface $aliases)
    {
        $this->aliases = $aliases;
    }

    public function getAliases(): AliasesInterface
    {
        return $this->aliases;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AliasesWrapper5EMA9
 * @covers \Tailors\Lib\Injector\AliasesWrapperTrait
 *
 * @internal
 */
final class AliasesWrapperTraitTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetAliases(): void
    {
        $wrappee = $this->createStub(AliasesInterface::class);

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertSame($wrappee, $wrapper->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasesArray(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('aliasesArray')
            ->willReturn(['foo' => 'bar'])
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertSame(['foo' => 'bar'], $wrapper->aliasesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->exactly(2))
            ->method('aliasExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertTrue($wrapper->aliasExists('foo'));
        $this->assertFalse($wrapper->aliasExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSet(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('aliasSet')
            ->with('foo', 'bar')
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertNull($wrapper->aliasSet('foo', 'bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('aliasUnset')
            ->with('foo')
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertNull($wrapper->aliasUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGet(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('aliasGet')
            ->with('foo')
            ->willReturn('bar')
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertSame('bar', $wrapper->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasResolve(): void
    {
        $wrappee = $this->getMockBuilder(AliasesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('aliasResolve')
            ->with('foo')
            ->willReturn('bar')
        ;

        $wrapper = new AliasesWrapper5EMA9($wrappee);

        $this->assertSame('bar', $wrapper->aliasResolve('foo'));
    }
}

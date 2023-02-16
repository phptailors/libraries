<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * Class using FactoriesWrapperTrait for testing purposes.
 */
final class FactoriesWrapper4VUP2 implements FactoriesInterface, FactoriesWrapperInterface
{
    use FactoriesWrapperTrait;

    private FactoriesInterface $factories;

    public function __construct(FactoriesInterface $factories)
    {
        $this->factories = $factories;
    }

    public function getFactories(): FactoriesInterface
    {
        return $this->factories;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\FactoriesWrapper4VUP2
 * @covers \Tailors\Lib\Injector\FactoriesWrapperTrait
 *
 * @internal
 */
final class FactoriesWrapperTraitTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetFactories(): void
    {
        $wrappee = $this->createStub(FactoriesInterface::class);

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertSame($wrappee, $wrapper->getFactories());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoriesArray(): void
    {
        $factory = $this->createStub(FactoryInterface::class);
        $wrappee = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('factoriesArray')
            ->willReturn(['foo' => $factory])
        ;

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertSame(['foo' => $factory], $wrapper->factoriesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryExists(): void
    {
        $wrappee = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $wrappee->expects($this->exactly(2))
            ->method('factoryExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertTrue($wrapper->factoryExists('foo'));
        $this->assertFalse($wrapper->factoryExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactorySet(): void
    {
        $factory = $this->createStub(FactoryInterface::class);
        $wrappee = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('factorySet')
            ->with('foo', $factory)
        ;

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertNull($wrapper->factorySet('foo', $factory));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryUnset(): void
    {
        $wrappee = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('factoryUnset')
            ->with('foo')
        ;

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertNull($wrapper->factoryUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryGet(): void
    {
        $factory = $this->createStub(FactoryInterface::class);
        $wrappee = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('factoryGet')
            ->with('foo')
            ->willReturn($factory)
        ;

        $wrapper = new FactoriesWrapper4VUP2($wrappee);

        $this->assertSame($factory, $wrapper->factoryGet('foo'));
    }
}

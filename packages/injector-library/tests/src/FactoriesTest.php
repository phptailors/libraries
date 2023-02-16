<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\Factories
 */
final class FactoriesTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsFactoriesInterface(): void
    {
        $this->assertImplementsInterface(FactoriesInterface::class, Factories::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoriesArray(): void
    {
        $foo = $this->createStub(FactoryInterface::class);
        $factories = new Factories(['foo' => $foo]);
        $this->assertSame(['foo' => $foo], $factories->factoriesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryExists(): void
    {
        $foo = $this->createStub(FactoryInterface::class);
        $factories = new Factories(['foo' => $foo]);
        $this->assertTrue($factories->factoryExists('foo'));
        $this->assertFalse($factories->factoryExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactorySet(): void
    {
        $foo = $this->createStub(FactoryInterface::class);
        $factories = new Factories();
        $factories->factorySet('foo', $foo);
        $this->assertSame(['foo' => $foo], $factories->factoriesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryUnset(): void
    {
        $foo = $this->createStub(FactoryInterface::class);
        $bar = $this->createStub(FactoryInterface::class);
        $factories = new Factories(['foo' => $foo, 'bar' => $bar]);
        $factories->factoryUnset('bar');
        $this->assertSame(['foo' => $foo], $factories->factoriesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryGet(): void
    {
        $foo = $this->createStub(FactoryInterface::class);
        $bar = $this->createStub(FactoryInterface::class);
        $factories = new Factories(['foo' => $foo, 'bar' => $bar]);
        $this->assertSame($foo, $factories->factoryGet('foo'));
        $this->assertSame($bar, $factories->factoryGet('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryGetThrowsNotFoundException(): void
    {
        $factories = new Factories();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('factory \'foo\' not found');

        $this->assertNull($factories->factoryGet('foo'));
    }
}

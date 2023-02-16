<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\Namespaces
 */
final class NamespacesTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsNamespacesInterface(): void
    {
        $this->assertImplementsInterface(NamespacesInterface::class, Namespaces::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsArray(): void
    {
        $foo = $this->createStub(ScopeInterface::class);
        $namespaces = new Namespaces(['foo' => $foo]);
        $this->assertSame(['foo' => $foo], $namespaces->nsArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsExists(): void
    {
        $foo = $this->createStub(ScopeInterface::class);
        $namespaces = new Namespaces(['foo' => $foo]);
        $this->assertTrue($namespaces->nsExists('foo'));
        $this->assertFalse($namespaces->nsExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsSet(): void
    {
        $foo = $this->createStub(ScopeInterface::class);
        $namespaces = new Namespaces();
        $namespaces->nsSet('foo', $foo);
        $this->assertSame(['foo' => $foo], $namespaces->nsArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsUnset(): void
    {
        $foo = $this->createStub(ScopeInterface::class);
        $bar = $this->createStub(ScopeInterface::class);
        $namespaces = new Namespaces(['foo' => $foo, 'bar' => $bar]);
        $namespaces->nsUnset('bar');
        $this->assertSame(['foo' => $foo], $namespaces->nsArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsGet(): void
    {
        $foo = $this->createStub(ScopeInterface::class);
        $bar = $this->createStub(ScopeInterface::class);
        $namespaces = new Namespaces(['foo' => $foo, 'bar' => $bar]);
        $this->assertSame($foo, $namespaces->nsGet('foo'));
        $this->assertSame($bar, $namespaces->nsGet('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsGetThrowsNotFoundException(): void
    {
        $namespaces = new Namespaces();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('namespace \'foo\' not found');

        $this->assertNull($namespaces->nsGet('foo'));
    }
}

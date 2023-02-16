<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\Instances
 */
final class InstancesTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInstancesInterface(): void
    {
        $this->assertImplementsInterface(InstancesInterface::class, Instances::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstancesArray(): void
    {
        $foo = $this->createStub(\stdClass::class);
        $instances = new Instances(['foo' => $foo]);
        $this->assertSame(['foo' => $foo], $instances->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceExists(): void
    {
        $foo = $this->createStub(\stdClass::class);
        $instances = new Instances(['foo' => $foo]);
        $this->assertTrue($instances->instanceExists('foo'));
        $this->assertFalse($instances->instanceExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceSet(): void
    {
        $foo = $this->createStub(\stdClass::class);
        $instances = new Instances();
        $instances->instanceSet('foo', $foo);
        $this->assertSame(['foo' => $foo], $instances->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceUnset(): void
    {
        $foo = $this->createStub(\stdClass::class);
        $bar = $this->createStub(\stdClass::class);
        $instances = new Instances(['foo' => $foo, 'bar' => $bar]);
        $instances->instanceUnset('bar');
        $this->assertSame(['foo' => $foo], $instances->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceGet(): void
    {
        $foo = $this->createStub(\stdClass::class);
        $bar = $this->createStub(\stdClass::class);
        $instances = new Instances(['foo' => $foo, 'bar' => $bar]);
        $this->assertSame($foo, $instances->instanceGet('foo'));
        $this->assertSame($bar, $instances->instanceGet('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceGetThrowsNotFoundException(): void
    {
        $instances = new Instances();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('instance \'foo\' not found');

        $this->assertNull($instances->instanceGet('foo'));
    }
}

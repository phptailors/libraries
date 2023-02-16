<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * Class using InstancesWrapperTrait for testing purposes.
 */
final class InstancesWrapper90ZU7 implements InstancesInterface, InstancesWrapperInterface
{
    use InstancesWrapperTrait;

    private InstancesInterface $instances;

    public function __construct(InstancesInterface $instances)
    {
        $this->instances = $instances;
    }

    public function getInstances(): InstancesInterface
    {
        return $this->instances;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\InstancesWrapper90ZU7
 * @covers \Tailors\Lib\Injector\InstancesWrapperTrait
 *
 * @internal
 */
final class InstancesWrapperTraitTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetInstances(): void
    {
        $wrappee = $this->createStub(InstancesInterface::class);

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertSame($wrappee, $wrapper->getInstances());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstancesArray(): void
    {
        $instance = $this->createStub(\stdClass::class);
        $wrappee = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('instancesArray')
            ->willReturn(['foo' => $instance])
        ;

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertSame(['foo' => $instance], $wrapper->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceExists(): void
    {
        $wrappee = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $wrappee->expects($this->exactly(2))
            ->method('instanceExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertTrue($wrapper->instanceExists('foo'));
        $this->assertFalse($wrapper->instanceExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceSet(): void
    {
        $instance = $this->createStub(\stdClass::class);
        $wrappee = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('instanceSet')
            ->with('foo', $instance)
        ;

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertNull($wrapper->instanceSet('foo', $instance));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceUnset(): void
    {
        $wrappee = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('instanceUnset')
            ->with('foo')
        ;

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertNull($wrapper->instanceUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceGet(): void
    {
        $instance = $this->createStub(\stdClass::class);
        $wrappee = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('instanceGet')
            ->with('foo')
            ->willReturn($instance)
        ;

        $wrapper = new InstancesWrapper90ZU7($wrappee);

        $this->assertSame($instance, $wrapper->instanceGet('foo'));
    }
}

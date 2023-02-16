<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Scope
 *
 * @internal
 */
final class ScopeTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsScopeInterface(): void
    {
        $this->assertImplementsInterface(ScopeInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsAliasesInterface(): void
    {
        $this->assertImplementsInterface(AliasesInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsAliasesWrapperInterface(): void
    {
        $this->assertImplementsInterface(AliasesWrapperInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInstancesInterface(): void
    {
        $this->assertImplementsInterface(InstancesInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInstancesWrapperInterface(): void
    {
        $this->assertImplementsInterface(InstancesWrapperInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesAliasesWrapperTrait(): void
    {
        $this->assertUsesTrait(AliasesWrapperTrait::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesInstancesWrapperTrait(): void
    {
        $this->assertUsesTrait(InstancesWrapperTrait::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetAliases(): void
    {
        $aliases = $this->createStub(AliasesInterface::class);
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $scope = new Scope($aliases, $instances);

        $this->assertSame($aliases, $scope->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasesArray(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasesArray')
            ->willReturn(['foo' => 'bar'])
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertSame(['foo' => 'bar'], $scope->aliasesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->exactly(2))
            ->method('aliasExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertTrue($scope->aliasExists('foo'));
        $this->assertFalse($scope->aliasExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasSet')
            ->with('foo', 'bar')
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertNull($scope->aliasSet('foo', 'bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasUnset')
            ->with('foo')
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertNull($scope->aliasUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasGet')
            ->with('foo')
            ->willReturn('bar')
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertSame('bar', $scope->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasResolve(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasResolve')
            ->with('foo')
            ->willReturn('bar')
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertSame('bar', $scope->aliasResolve('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetInstances(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $scope = new Scope($aliases, $instances);

        $this->assertSame($instances, $scope->getInstances());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstancesArray(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instancesArray')
            ->willReturn(['foo' => $instance])
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertSame(['foo' => $instance], $scope->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceExists(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $instances->expects($this->exactly(2))
            ->method('instanceExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertTrue($scope->instanceExists('foo'));
        $this->assertFalse($scope->instanceExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceSet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instanceSet')
            ->with('foo', $instance)
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertNull($scope->instanceSet('foo', $instance));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceUnset(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $instances->expects($this->once())
            ->method('instanceUnset')
            ->with('foo')
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertNull($scope->instanceUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceGet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instanceGet')
            ->with('foo')
            ->willReturn($instance)
        ;

        $scope = new Scope($aliases, $instances);

        $this->assertSame($instance, $scope->instanceGet('foo'));
    }
}

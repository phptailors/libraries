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
    public function testImplementsFactoriesInterface(): void
    {
        $this->assertImplementsInterface(FactoriesInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsFactoriesWrapperInterface(): void
    {
        $this->assertImplementsInterface(FactoriesWrapperInterface::class, Scope::class);
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
    public function testUsesFactoriesWrapperTrait(): void
    {
        $this->assertUsesTrait(FactoriesWrapperTrait::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetAliases(): void
    {
        $aliases = $this->createStub(AliasesInterface::class);
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame($aliases, $scope->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasesArray(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasesArray')
            ->willReturn(['foo' => 'bar'])
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame(['foo' => 'bar'], $scope->aliasesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->exactly(2))
            ->method('aliasExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $scope = new Scope($aliases, $instances, $factories);

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
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasSet')
            ->with('foo', 'bar')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->aliasSet('foo', 'bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasUnset')
            ->with('foo')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->aliasUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasGet')
            ->with('foo')
            ->willReturn('bar')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame('bar', $scope->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasResolve(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $aliases->expects($this->once())
            ->method('aliasResolve')
            ->with('foo')
            ->willReturn('bar')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame('bar', $scope->aliasResolve('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetInstances(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame($instances, $scope->getInstances());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstancesArray(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instancesArray')
            ->willReturn(['foo' => $instance])
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame(['foo' => $instance], $scope->instancesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceExists(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instances->expects($this->exactly(2))
            ->method('instanceExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $scope = new Scope($aliases, $instances, $factories);

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
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instanceSet')
            ->with('foo', $instance)
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->instanceSet('foo', $instance));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceUnset(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instances->expects($this->once())
            ->method('instanceUnset')
            ->with('foo')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->instanceUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceGet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instance = $this->createStub(\stdClass::class);
        $instances->expects($this->once())
            ->method('instanceGet')
            ->with('foo')
            ->willReturn($instance)
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame($instance, $scope->instanceGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetFactories(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame($factories, $scope->getFactories());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoriesArray(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $factory = $this->createStub(FactoryInterface::class);
        $factories->expects($this->once())
            ->method('factoriesArray')
            ->willReturn(['foo' => $factory])
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame(['foo' => $factory], $scope->factoriesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryExists(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $factories->expects($this->exactly(2))
            ->method('factoryExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertTrue($scope->factoryExists('foo'));
        $this->assertFalse($scope->factoryExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactorySet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $factory = $this->createStub(FactoryInterface::class);
        $factories->expects($this->once())
            ->method('factorySet')
            ->with('foo', $factory)
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->factorySet('foo', $factory));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryUnset(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $factories->expects($this->once())
            ->method('factoryUnset')
            ->with('foo')
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertNull($scope->factoryUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFactoryGet(): void
    {
        $aliases = $this->getMockBuilder(AliasesInterface::class)->getMock();
        $instances = $this->getMockBuilder(InstancesInterface::class)->getMock();
        $factories = $this->getMockBuilder(FactoriesInterface::class)->getMock();

        $instance = $this->createStub(FactoryInterface::class);
        $factories->expects($this->once())
            ->method('factoryGet')
            ->with('foo')
            ->willReturn($instance)
        ;

        $scope = new Scope($aliases, $instances, $factories);

        $this->assertSame($instance, $scope->factoryGet('foo'));
    }
}

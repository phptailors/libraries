<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\NotFoundException;
use Tailors\Lib\Injector\ScopeContainer;
use Tailors\Lib\Injector\ScopeContainerInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

interface Dummy4WUQ3Interface
{
}

final class Dummy4WUQ3 implements Dummy4WUQ3Interface
{
}

/**
 * @covers \Tailors\Lib\Injector\ScopeContainer
 *
 * @internal
 */
final class ScopeContainerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsScopeContainerInterface(): void
    {
        $this->assertImplementsInterface(ScopeContainerInterface::class, ScopeContainer::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArguments(): void
    {
        $container = new ScopeContainer();
        $this->assertEmpty($container->getAliases());
        $this->assertEmpty($container->getInstances());
        $this->assertEmpty($container->getVariables());
        $this->assertEmpty($container->getParameters());
        $this->assertEmpty($container->getTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliases(): void
    {
        $aliases = ['foo' => 'FOO'];
        $container = new ScopeContainer(['aliases' => $aliases]);
        $this->assertSame($aliases, $container->getAliases());

        $this->assertTrue($container->hasAlias('foo'));
        $this->assertFalse($container->hasAlias('Foo'));
        $this->assertSame('FOO', $container->getAlias('foo'));

        $this->assertFalse($container->hasAlias('bar'));
        $this->assertNull($container->setAlias('bar', 'BAR'));
        $this->assertTrue($container->hasAlias('bar'));
        $this->assertSame('BAR', $container->getAlias('bar'));

        $this->assertNull($container->unsetAlias('bar'));
        $this->assertTrue($container->hasAlias('foo'));
        $this->assertFalse($container->hasAlias('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetAliasThrowsNotFoundException(): void
    {
        $container = new ScopeContainer();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('alias %s not found', var_export('foo', true)));

        $this->assertNull($container->getAlias('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstances(): void
    {
        $dummy = new Dummy4WUQ3();
        $instances = [Dummy4WUQ3::class => $dummy];
        $container = new ScopeContainer(['instances' => $instances]);
        $this->assertSame(array_change_key_case($instances, CASE_LOWER), $container->getInstances());

        $this->assertTrue($container->hasInstance(Dummy4WUQ3::class));
        $this->assertFalse($container->hasInstance(Dummy4WUQ3Interface::class));
        $this->assertSame($dummy, $container->getInstance(Dummy4WUQ3::class));
        $this->assertSame($dummy, $container->getInstance(strtolower(Dummy4WUQ3::class)));

        $this->assertNull($container->setInstance(Dummy4WUQ3Interface::class, $dummy));
        $this->assertTrue($container->hasInstance(Dummy4WUQ3Interface::class));
        $this->assertSame($dummy, $container->getInstance(Dummy4WUQ3Interface::class));
        $this->assertSame($dummy, $container->getInstance(strtolower(Dummy4WUQ3Interface::class)));

        $this->assertNull($container->unsetInstance(Dummy4WUQ3Interface::class));
        $this->assertTrue($container->hasInstance(Dummy4WUQ3::class));
        $this->assertFalse($container->hasInstance(Dummy4WUQ3Interface::class));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetInstanceThrowsNotFoundException(): void
    {
        $container = new ScopeContainer();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('shared instance %s not found', var_export(Dummy4WUQ3::class, true)));

        $this->assertNull($container->getInstance(Dummy4WUQ3::class));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testVariables(): void
    {
        $dummy = new Dummy4WUQ3();
        $binding = fn (): Dummy4WUQ3 => $dummy;
        $bindings = ['varName' => $binding];
        $container = new ScopeContainer(['variables' => $bindings]);
        $this->assertSame($bindings, $container->getVariables());

        $this->assertTrue($container->hasVariable('varName'));
        $this->assertFalse($container->hasVariable('varname'));
        $this->assertFalse($container->hasVariable('otherVar'));
        $this->assertSame($binding, $container->getVariable('varName'));

        $this->assertNull($container->bindVariable('otherVar', $binding));
        $this->assertTrue($container->hasVariable('otherVar'));
        $this->assertFalse($container->hasVariable('othervar'));
        $this->assertSame($binding, $container->getVariable('otherVar'));

        $this->assertNull($container->unbindVariable('otherVar'));
        $this->assertTrue($container->hasVariable('varName'));
        $this->assertFalse($container->hasVariable('otherVar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetVariableThrowsNotFoundException(): void
    {
        $container = new ScopeContainer();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('binding for variable %s not found', var_export('$varName', true)));

        $this->assertNull($container->getVariable('varName'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testParameters(): void
    {
        $binding = fn (): string => 'FOO';
        $bindings = ['someFunc' => [0 => $binding, 'foo' => $binding]];
        $container = new ScopeContainer(['parameters' => $bindings]);
        $this->assertSame(array_change_key_case($bindings, CASE_LOWER), $container->getParameters());

        $this->assertTrue($container->hasParameter('someFunc', 0));
        $this->assertTrue($container->hasParameter('somefunc', 0));
        $this->assertTrue($container->hasParameter('someFunc', 'foo'));
        $this->assertTrue($container->hasParameter('somefunc', 'foo'));
        $this->assertFalse($container->hasParameter('someFunc', 'Dummy'));
        $this->assertFalse($container->hasParameter('otherFunc', 0));
        $this->assertSame($binding, $container->getParameter('someFunc', 0));
        $this->assertSame($binding, $container->getParameter('somefunc', 0));
        $this->assertSame($binding, $container->getParameter('someFunc', 'foo'));
        $this->assertSame($binding, $container->getParameter('somefunc', 'foo'));

        $this->assertNull($container->bindParameter('otherFunc', 0, $binding));
        $this->assertTrue($container->hasParameter('otherFunc', 0));
        $this->assertTrue($container->hasParameter('otherfunc', 0));
        $this->assertSame($binding, $container->getParameter('otherFunc', 0));

        $this->assertNull($container->unbindParameter('otherFunc', 0));
        $this->assertFalse($container->hasParameter('otherFunc', 0));
        $this->assertSame(['somefunc' => [0 => $binding, 'foo' => $binding]], $container->getParameters());
        $this->assertNull($container->unbindParameter('someFunc', 0));
        $this->assertSame(['somefunc' => ['foo' => $binding]], $container->getParameters());
        $this->assertNull($container->unbindParameter('someFunc', 'foo'));
        $this->assertSame([], $container->getParameters());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetParameterThrowsNotFoundExceptionOnUnknownFunction(): void
    {
        $container = new ScopeContainer();
        $message = sprintf('bindings for parameters of function %s not found', var_export('inexistent', true));
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($container->getParameter('inexistent', 0));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetParameterThrowsNotFoundExceptionOnUnknonwnPositionalParam(): void
    {
        $container = new ScopeContainer(['parameters' => ['foo' => []]]);
        $message = 'binding for function foo() parameter #1 not found';
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($container->getParameter('foo', 1));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetParameterThrowsNotFoundExceptionOnUnknonwnNamedParam(): void
    {
        $container = new ScopeContainer(['parameters' => ['foo' => []]]);
        $message = sprintf('binding for function foo() parameter %s not found', var_export('$bar', true));
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($container->getParameter('foo', 'bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTypes(): void
    {
        $dummy = new Dummy4WUQ3();
        $binding = fn (): Dummy4WUQ3 => $dummy;
        $bindings = [Dummy4WUQ3::class => $binding];
        $container = new ScopeContainer(['types' => $bindings]);
        $this->assertSame(array_change_key_case($bindings, CASE_LOWER), $container->getTypes());

        $this->assertTrue($container->hasType(Dummy4WUQ3::class));
        $this->assertTrue($container->hasType(strtolower(Dummy4WUQ3::class)));
        $this->assertFalse($container->hasType(Dummy4WUQ3Interface::class));
        $this->assertSame($binding, $container->getType(Dummy4WUQ3::class));

        $this->assertNull($container->bindType(Dummy4WUQ3Interface::class, $binding));
        $this->assertTrue($container->hasType(Dummy4WUQ3Interface::class));
        $this->assertTrue($container->hasType(strtolower(Dummy4WUQ3Interface::class)));
        $this->assertSame($binding, $container->getType(Dummy4WUQ3Interface::class));

        $this->assertNull($container->unbindType(Dummy4WUQ3Interface::class));
        $this->assertTrue($container->hasType(Dummy4WUQ3::class));
        $this->assertFalse($container->hasType(Dummy4WUQ3Interface::class));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetTypeThrowsNotFoundException(): void
    {
        $container = new ScopeContainer();
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('binding for type %s not found', var_export(Dummy4WUQ3::class, true)));

        $this->assertNull($container->getType(Dummy4WUQ3::class));
    }
}

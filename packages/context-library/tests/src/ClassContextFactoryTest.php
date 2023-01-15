<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\AbstractManagedContextFactory;
use Tailors\Lib\Context\ClassContextFactory;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\PHPUnit\ExtendsClassTrait;

final class ClassYVO2VPY5
{
}

final class ClassJG8MG9JQ
{
}

abstract class BaseContextOLESLFOV implements ContextManagerInterface
{
    public mixed $wrapped;

    public function __construct(mixed $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    public function enterContext(): self
    {
        return $this;
    }

    public function exitContext(\Throwable $exception = null): bool
    {
        return false;
    }
}

final class ContextYVO2VPY5 extends BaseContextOLESLFOV
{
}

final class ContextJG8MG9JQ extends BaseContextOLESLFOV
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\ClassContextFactory
 *
 * @internal
 */
final class ClassContextFactoryTest extends TestCase
{
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractManagedContextFactory(): void
    {
        $this->assertExtendsClass(AbstractManagedContextFactory::class, ClassContextFactory::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArgs(): void
    {
        $factory = new ClassContextFactory();
        $this->assertEquals([], $factory->getRegistry());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithSomeWrappers(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class      => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function (mixed $wrapped): ContextJG8MG9JQ {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);
        $registry = $factory->getRegistry();

        $this->assertEquals(2, count($registry));

        $this->assertTrue(isset($registry[ClassYVO2VPY5::class]));
        $this->assertTrue(isset($registry[ClassJG8MG9JQ::class]));

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $obj1 = new ClassYVO2VPY5();
        $ctx1 = call_user_func($registry[ClassYVO2VPY5::class], $obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $obj2 = new ClassJG8MG9JQ();
        $ctx2 = call_user_func($registry[ClassJG8MG9JQ::class], $obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRegister(): void
    {
        $factory = new ClassContextFactory();
        $factory->register(ClassYVO2VPY5::class, ContextYVO2VPY5::class);
        $factory->register('\\'.ClassJG8MG9JQ::class, function ($wrapped) {
            return new ContextJG8MG9JQ($wrapped);
        });

        $registry = $factory->getRegistry();

        $this->assertEquals(2, count($registry));

        $this->assertTrue(isset($registry[ClassYVO2VPY5::class]));
        $this->assertTrue(isset($registry[ClassJG8MG9JQ::class]));

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $obj1 = new ClassYVO2VPY5();
        $ctx1 = call_user_func($registry[ClassYVO2VPY5::class], $obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $obj2 = new ClassJG8MG9JQ();
        $ctx2 = call_user_func($registry[ClassJG8MG9JQ::class], $obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRegisterWithContextManagerNotAClassString(): void
    {
        $factory = new ClassContextFactory();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'argument 2 to '.ClassContextFactory::class.'::register()'.
            ' must be a callable or a class name, string given'
        );
        $factory->register(ClassJG8MG9JQ::class, 'In-Ex-Is-Tent');
    }

    /**
     * @psalm-return array<array>
     */
    public function providerRegisterWithContextManagerOfInvalidType(): array
    {
        return [
            [null],
            [123],
            [new \Exception()],
        ];
    }

    /**
     * @dataProvider providerRegisterWithContextManagerOfInvalidType
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRegisterWithContextManagerOfInvalidType(mixed $arg): void
    {
        $factory = new ClassContextFactory();

        $this->expectException(\TypeError::class);

        /** @psalm-suppress MixedArgument */
        $factory->register(ClassJG8MG9JQ::class, $arg);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRemove(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class      => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function (mixed $wrapped): ContextJG8MG9JQ {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $registry = $factory->getRegistry();

        $factory->remove('In-Ex-Is-Tent');

        $this->assertEquals(2, count($registry));

        $this->assertTrue(isset($registry[ClassYVO2VPY5::class]));
        $this->assertTrue(isset($registry[ClassJG8MG9JQ::class]));

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $factory->remove(ClassYVO2VPY5::class);
        $registry = $factory->getRegistry();

        $this->assertEquals(1, count($registry));

        $this->assertTrue(isset($registry[ClassJG8MG9JQ::class]));

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $factory->remove(ClassJG8MG9JQ::class);
        $registry = $factory->getRegistry();

        $this->assertEquals(0, count($registry));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManager(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class      => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function (mixed $wrapped): ContextJG8MG9JQ {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $obj1 = new ClassYVO2VPY5();
        $obj2 = new ClassJG8MG9JQ();

        $ctx1 = $factory->getContextManager($obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $ctx2 = $factory->getContextManager($obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerWithNonObject(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class      => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function (mixed $wrapped): ContextJG8MG9JQ {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $this->assertNull($factory->getContextManager('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerWithUnregisteredObject(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
        ];

        $factory = new ClassContextFactory($wrappers);

        $this->assertNull($factory->getContextManager(new ClassJG8MG9JQ()));
    }
}

// vim: syntax=php sw=4 ts=4 et:

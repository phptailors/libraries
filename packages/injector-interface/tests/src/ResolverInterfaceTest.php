<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ResolverH7Q6E implements ResolverInterface
{
    use ResolverInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ResolverInterfaceTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ResolverInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): ResolverH7Q6E
    {
        return new ResolverH7Q6E();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResolverInterface::class, $dummy);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provResolveReturnsMixed(): iterable
    {
        return [
            'null'   => [null],
            'bool'   => [true],
            'string' => [''],
            'int'    => [0],
            'float'  => [0.00],
            'array'  => [[]],
            'object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provResolveReturnsMixed
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveReturnsMixed(mixed $retval): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resolve = $retval;
        $this->assertSame($dummy->resolve, $dummy->resolve(''));
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provResolveWithInvalidParamType(): iterable
    {
        return [
            'null'   => [null],
            'bool'   => [true],
            'int'    => [0],
            'float'  => [0.00],
            'array'  => [[]],
            'object' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provResolveWithInvalidParamType
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveWithInvalidParamType(mixed $id): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolve = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('string');

        /** @psalm-suppress MixedArgument */
        $dummy->resolve($id);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed, list{class-string}}>
     */
    public static function provResolveClassReturnsObject(): iterable
    {
        return [
            '#00' => [new \stdClass(), [\stdClass::class]],
        ];
    }

    /**
     * @dataProvider provResolveClassReturnsObject
     *
     * @psalm-param list{class-string} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveClassReturnsObject(mixed $retval, array $args): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resolveClass = $retval;
        $this->assertSame($dummy->resolveClass, $dummy->resolveClass(...$args));
        $this->assertIsObject($dummy->resolveClass(...$args));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed}}>
     */
    public static function provResolveClassWithInvalidParamType(): iterable
    {
        return [
            '(null)'   => [[null]],
            '(bool)'   => [[true]],
            '(int)'    => [[0]],
            '(float)'  => [[0.00]],
            '(array)'  => [[[]]],
            '(object)' => [[new \stdClass()]],
        ];
    }

    /**
     * @dataProvider provResolveClassWithInvalidParamType
     *
     * @psalm-param list{mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveClassWithInvalidParamType(array $args): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolveClass = new \stdClass();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('string');

        /** @psalm-suppress MixedArgument */
        $dummy->resolveClass(...$args);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provResolveClassWithInvalidReturnType(): iterable
    {
        return [
            'bool'   => [true],
            'int'    => [0],
            'float'  => [0.00],
            'string' => [''],
            'array'  => [[]],
        ];
    }

    /**
     * @dataProvider provResolveClassWithInvalidReturnType
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveClassWithInvalidReturnType(mixed $retval): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolveClass = $retval;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('object');

        $dummy->resolveClass(\stdClass::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:

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
final class ContainerLV2QQ implements ContainerInterface
{
    use ContainerInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ContainerInterfaceTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContainerInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): ContainerLV2QQ
    {
        return new ContainerLV2QQ();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContainerInterface::class, $dummy);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provGetReturnsMixed(): iterable
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
     * @dataProvider provGetReturnsMixed
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetReturnsMixed(mixed $retval): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->get = $retval;
        $this->assertSame($dummy->get, $dummy->get(''));
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provGetWithInvalidParamType(): iterable
    {
        return [
            '(null)'   => [null],
            '(bool)'   => [true],
            '(int)'    => [0],
            '(float)'  => [0.00],
            '(array)'  => [[]],
            '(object)' => [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provGetWithInvalidParamType
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetWithInvalidParamType(mixed $id): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->get = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('string');

        /** @psalm-suppress MixedArgument */
        $dummy->get($id);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed}>
     */
    public static function provHasReturnsBool(): iterable
    {
        return [
            'true'  => [true],
            'false' => [false],
        ];
    }

    /**
     * @dataProvider provHasReturnsBool
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasReturnsBool(mixed $retval): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->has = $retval;
        $this->assertSame($dummy->has, $dummy->has(''));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed},string}>
     */
    public static function provHasWithInvalidParamType(): iterable
    {
        return [
            '(null)'   => [[null], 'string'],
            '(bool)'   => [[true], 'string'],
            '(int)'    => [[0], 'string'],
            '(float)'  => [[0.00], 'string'],
            '(array)'  => [[[]], 'string'],
            '(object)' => [[new \stdClass()], 'string'],
        ];
    }

    /**
     * @dataProvider provHasWithInvalidParamType
     *
     * @psalm-param list{mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->has = true;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->has(...$args);
    }

    /**
     * @psalm-return iterable<array-key, list{mixed,string}>
     */
    public static function provHasWithInvalidReturnType(): iterable
    {
        return [
            'null'   => [null, 'bool'],
            'int'    => [0, 'bool'],
            'string' => ['', 'bool'],
            'float'  => [0.00, 'bool'],
            'array'  => [[], 'bool'],
            'object' => [new \stdClass(), 'bool'],
        ];
    }

    /**
     * @dataProvider provHasWithInvalidReturnType
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasWithInvalidReturnType(mixed $retval, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->has = $retval;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        $dummy->has('');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasReturnsNull(): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->alias('', ''));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed,mixed},string}>
     */
    public static function provAliasWithInvalidParamType(): iterable
    {
        $nonStringSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'array'  => [],
            'object' => new \stdClass(),
        ];

        // non-string #1 parameter
        foreach ($nonStringSamples as $type => $value) {
            yield '('.$type.',string)' => [[$value, ''], 'string'];
        }

        // non-string #2 parameter
        foreach ($nonStringSamples as $type => $value) {
            yield '(string,'.$type.')' => [['', $value], 'string'];
        }
    }

    /**
     * @dataProvider provAliasWithInvalidParamType
     *
     * @psalm-param list{mixed,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->alias(...$args);
    }

    /**
     * @psalm-return iterable<array-key, list{list{string,mixed}}>
     */
    public static function provInstanceReturnsNull(): iterable
    {
        $mixedSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'string' => '',
            'array'  => [],
            'object' => new \stdClass(),
        ];

        // mixed parameter #2
        foreach ($mixedSamples as $type => $value) {
            yield '(string,'.$type.')' => [['', $value]];
        }
    }

    /**
     * @dataProvider provInstanceReturnsNull
     *
     * @psalm-param list{string,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceReturnsNull(array $args): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->instance(...$args));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed,mixed},string}>
     */
    public static function provInstanceWithInvalidParamType(): iterable
    {
        $nonStringSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'array'  => [],
            'object' => new \stdClass(),
        ];

        // non-string #1 parameter
        foreach ($nonStringSamples as $type => $value) {
            yield '('.$type.'null)' => [[$value, null], 'string'];
        }
    }

    /**
     * @dataProvider provInstanceWithInvalidParamType
     *
     * @psalm-param list{mixed,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->instance(...$args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testBindReturnsNull(): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->bind('', fn (): string => ''));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed,mixed},string}>
     */
    public static function provBindWithInvalidParamType(): iterable
    {
        $nonStringSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'array'  => [],
            'object' => new \stdClass(),
        ];

        $nonClosureSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'string' => '',
            'array'  => [],
            'object' => new \stdClass(),
        ];

        // non-string #1 parameter
        foreach ($nonStringSamples as $type => $value) {
            yield '('.$type.'Closure)' => [[$value, fn (): mixed => null], 'string'];
        }

        // non-closuer #2 parameter
        foreach ($nonClosureSamples as $type => $value) {
            yield '(string,'.$type.')' => [['', $value], 'Closure'];
        }
    }

    /**
     * @dataProvider provBindWithInvalidParamType
     *
     * @psalm-param list{mixed,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testBindWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->bind(...$args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSingletonReturnsNull(): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->singleton('', fn (): string => ''));
    }

    /**
     * @psalm-return iterable<array-key, list{list{mixed,mixed},string}>
     */
    public static function provSingletonWithInvalidParamType(): iterable
    {
        $nonStringSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'array'  => [],
            'object' => new \stdClass(),
        ];

        $nonClosureSamples = [
            'null'   => null,
            'bool'   => true,
            'int'    => 0,
            'float'  => 0.0,
            'string' => '',
            'array'  => [],
            'object' => new \stdClass(),
        ];

        // non-string #1 parameter
        foreach ($nonStringSamples as $type => $value) {
            yield '('.$type.'Closure)' => [[$value, fn (): mixed => null], 'string'];
        }

        // non-closuer #2 parameter
        foreach ($nonClosureSamples as $type => $value) {
            yield '(string,'.$type.')' => [['', $value], 'Closure'];
        }
    }

    /**
     * @dataProvider provSingletonWithInvalidParamType
     *
     * @psalm-param list{mixed,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSingletonWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->singleton(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:

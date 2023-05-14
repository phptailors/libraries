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
final class InjectionAX9Z2 implements InjectionInterface
{
    use InjectionInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\InjectionInterfaceTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class InjectionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): InjectionAX9Z2
    {
        return new InjectionAX9Z2();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(InjectionInterface::class, $dummy);
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{string,\Closure(ResolverInterface):mixed},
     *      mixed
     * }>
     */
    public static function provClassReturnsMixed(): iterable
    {
        $values = [
            'object' => new \stdClass(),
            'null'   => null,
            'bool'   => true,
            'int'    => 123,
            'float'  => 1.23,
            'string' => 'foo',
            'array'  => [],
        ];

        foreach ($values as $type => $value) {
            yield $type => [['id', fn () => $value], $value];
        }
    }

    /**
     * @dataProvider provClassReturnsMixed
     *
     * @psalm-param list{string,\Closure(ResolverInterface):mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassReturnsMixed(array $args, mixed $class): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->class = $class;
        $this->assertSame($class, $dummy->class(...$args));
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{mixed,mixed},
     *      string
     * }>
     */
    public static function provClassWithInvalidParamType(): iterable
    {
        $nonStringValues = [
            'object' => new \stdClass(),
            'null'   => null,
            'bool'   => true,
            'int'    => 123,
            'float'  => 1.23,
            'array'  => [],
        ];

        $nonClosureValues = [
            'object' => new \stdClass(),
            'null'   => null,
            'bool'   => true,
            'int'    => 123,
            'float'  => 1.23,
            'string' => 'foo',
            'array'  => [],
        ];

        foreach ($nonStringValues as $type => $value) {
            yield '('.$type.',\Closure)' => [[$value, fn (): int => 0], 'string'];
        }

        foreach ($nonClosureValues as $type => $value) {
            yield '(string,'.$type.')' => [['', $value], 'Closure'];
        }
    }

    /**
     * @dataProvider provClassWithInvalidParamType
     *
     * @psalm-param list{mixed,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassWithInvalidParamType(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->class = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $this->assertNull($dummy->class(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et:

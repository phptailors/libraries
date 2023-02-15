<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class ResolverZMYOR implements ResolverInterface
{
    use ResolverInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ResolverInterfaceTrait
 *
 * @internal
 */
final class ResolverInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): ResolverZMYOR
    {
        return new ResolverZMYOR();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyInstance(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResolverInterface::class, $dummy);
    }

    /**
     * @psalm-return list<list{0: mixed, 1:list{0:string,1?:array}}>
     */
    public static function provResolveWithValidArguments(): array
    {
        return [
            ['return string', ['foo']],
            [null, ['foo']],
            [123, ['foo']],
            [new \Exception(), ['foo']],
            ['return string', ['foo', []]],
            ['return string', ['foo', [1, 'b']]],
        ];
    }

    /**
     * @psalm-return list<list{list{0:mixed,1?:mixed}, string}>
     */
    public static function provResolveWithInvalidArguments(): array
    {
        return [
            [[null], 'Argument #1'],
            [[123], 'Argument #1'],
            [[new \Exception()], 'Argument #1'],
            [[123, []], 'Argument #1'],
            [[[self::class, 'inexistent']], 'Argument #1'],
            [['ok', 'not ok'], 'Argument #2'],
        ];
    }

    /**
     * @dataProvider provResolveWithValidArguments
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list{0:string,1?:array} $args
     */
    public function testResolveWithValidArguments(mixed $retval, array $args): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolve = $retval;
        $this->assertSame($retval, $dummy->resolve(...$args));
    }

    /**
     * @dataProvider provResolveWithInvalidArguments
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list{0:mixed,1?:mixed} $args
     */
    public function testResolveWithInvalidArguments(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolve = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $this->assertNull($dummy->resolve(...$args));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveWithMissingArgument(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resolve = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Too few');

        /** @psalm-suppress TooFewArguments */
        $this->assertNull($dummy->resolve());
    }
}

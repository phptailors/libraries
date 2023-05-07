<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\CircularDependencyException
 */
final class CircularDependencyExceptionTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsException(): void
    {
        $this->assertExtendsClass(\Exception::class, CircularDependencyException::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsCircularDependencyExceptionInterface(): void
    {
        $this->assertImplementsInterface(
            CircularDependencyExceptionInterface::class,
            CircularDependencyException::class
        );
    }

    /**
     * @psalm-return iterable<array-key, list{
     *  list{0: array<string>, 1: string, 2?: int, 3?: \Throwable},
     *  array{message: mixed, code?: mixed, previous?: mixed}
     * }>
     */
    public static function provFromSeenAndFound(): iterable
    {
        $previous = new \Exception();

        return [
            '#00' => [
                [[], ''],
                [
                    'message'  => 'circular dependency',
                    'code'     => 0,
                    'previous' => null,
                ],
            ],
            '#01' => [
                [['b'], 'a'],
                [
                    'message' => 'circular dependency',
                ],
            ],
            '#02' => [
                [['b', 'c', 'd'], 'a'],
                [
                    'message' => 'circular dependency',
                ],
            ],
            '#03' => [
                [['b', 'b', 'c', 'd'], 'c'],
                [
                    'message' => 'circular dependency: \'c\' -> \'d\' -> \'c\'',
                ],
            ],
            '#04' => [
                [[], '', 123, $previous],
                [
                    'message'  => 'circular dependency',
                    'code'     => 123,
                    'previous' => $previous,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provFromSeenAndFound
     *
     * @psalm-param list{0: array<string>, 1: string, 2?: int, 3?: \Throwable} $args
     * @psalm-param array{message: mixed, code?: mixed, previous?: mixed} $expected
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromSeenAndFound(array $args, array $expected): void
    {
        $exception = CircularDependencyException::fromSeenAndFound(...$args);
        $this->assertSame($expected['message'], $exception->getMessage());
        if (array_key_exists('code', $expected)) {
            $this->assertSame($expected['code'], $exception->getCode());
        }
        if (array_key_exists('previous', $expected)) {
            $this->assertSame($expected['previous'], $exception->getPrevious());
        }
    }
}

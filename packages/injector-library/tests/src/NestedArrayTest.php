<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\NestedArray
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class NestedArrayTest extends TestCase
{
    /**
     * @psalm-return iterable<array-key, list{array, list<array-key>, list{0: bool, 1?: mixed}}>
     */
    public static function provNestedArrayGet(): iterable
    {
        return [
            '#00' => [
                [],
                [],
                [true, []]
            ],
            '#01' => [
                ['a' => 'A'],
                [],
                [true, ['a' => 'A']],
            ],
            '#02' => [
                ['a' => 'A'],
                [0],
                [false],
            ],
            '#03' => [
                ['a' => 'A'],
                ['b'],
                [false],
            ],
            '#04' => [
                ['a' => 'A', 'b' => [ 'b.a' => 'B.A', 'b.b' => 'B.B']],
                ['a'],
                [true, 'A'],
            ],
            '#05' => [
                ['a' => 'A', 'b' => [ 'b.a' => 'B.A', 'b.b' => 'B.B']],
                ['b'],
                [true, ['b.a' => 'B.A', 'b.b' => 'B.B']],
            ],
            '#06' => [
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
                ['b', 'b.a'],
                [true, 'B.A'],
            ],
            '#07' => [
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
                ['b', 'b.b'],
                [true, 'B.B'],
            ],
            '#08' => [
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
                ['b', 'b.b'],
                [true, 'B.B'],
            ],
            '#09' => [
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
                ['b', 'b.b', 'c.b.a'],
                [false],
            ],
        ];
    }

    /**
     * @dataProvider provNestedArrayGet
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list<array-key> $path
     * @psalm-param list{0: bool, 1?: mixed} $expected
     */
    public function testNestedArrayGet(array $array, array $path, array $expected): void
    {
        if (count($expected) >= 2) {
            $this->assertSame($expected[0], NestedArray::get($array, $path, $retval));
            $this->assertSame($expected[1], $retval);
        } else {
            $this->assertSame($expected[0], NestedArray::get($array, $path));
        }
    }

    /**
     * @psalm-return iterable<array-key, list{array, list<array-key>, mixed, mixed}>
     */
    public static function provNestedArraySet(): iterable
    {
        return [
            '#00' => [
                [],
                [],
                'A',
                'A',
            ],
            '#01' => [
                ['A'],
                [],
                'B',
                'B',
            ],
            '#02' => [
                [],
                ['a'],
                'A',
                ['a' => 'A'],
            ],
            '#03' => [
                [],
                ['a', 'b'],
                'A.B',
                ['a' => ['b' => 'A.B']],
            ],
            '#04' => [
                ['a' => ['c' => 'A.C']],
                ['a', 'b'],
                'A.B',
                ['a' => ['c' => 'A.C', 'b' => 'A.B']],
            ],
            '#05' => [
                ['a' => ['c' => 'A.C']],
                ['a'],
                'A',
                ['a' => 'A'],
            ],
            '#06' => [
                ['a' => ['c' => 'A.C']],
                ['a', 'b', 'd'],
                'A.B.D',
                ['a' => ['c' => 'A.C', 'b' => ['d' => 'A.B.D']]],
            ],
            '#07' => [
                ['a' => ['b' => 'A.B']],
                ['a', 'b', 'c'],
                'A.B.C',
                ['a' => ['b' => ['c' => 'A.B.C']]],
            ],
        ];
    }

    /**
     * @dataProvider provNestedArraySet
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list<array-key> $path
     */
    public function testNestedArraySet(array $array, array $path, mixed $value, mixed $expected): void
    {
        NestedArray::set($array, $path, $value);
        $this->assertSame($expected, $array);
    }
}

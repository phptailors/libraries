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
                [true, []],
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
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
                ['a'],
                [true, 'A'],
            ],
            '#05' => [
                ['a' => 'A', 'b' => ['b.a' => 'B.A', 'b.b' => 'B.B']],
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

    /**
     * @psalm-return iterable<array-key, list{
     *      array,
     *      array<array-key|array>,
     *      list{0: mixed, 1?: mixed}
     * }>
     */
    public static function provNestedArrayLookup(): iterable
    {
        return [
            '#00' => [
                ['A'],
                [], [[], ['A']],
            ],

            '#01' => [
                [],
                ['a'], [null],
            ],

            '#02' => [
                ['a' => 'A'],
                ['a'], [['a'], 'A'],
            ],

            '#03' => [
                ['a' => 'A'],
                [[], 'a'], [['a'], 'A'],
            ],

            '#04' => [
                ['a' => 'A'],
                ['a', 'a'], [null],
            ],

            '#05' => [
                ['a' => 'A'],
                [['a'], 'a'], [null],
            ],

            '#06' => [
                ['a' => ['b' => 'A.B']],
                ['a', 'b'], [['a', 'b'], 'A.B'],
            ],

            '#07' => [
                ['a' => ['b' => 'A.B']],
                ['a', 'c', 'b'], [null],
            ],

            '#08' => [
                ['a' => ['b' => 'A.B']],
                [['a', 'c'], 'b'], [['a', 'b'], 'A.B'],
            ],

            '#09' => [
                ['a' => ['b' => 'A.B'], 'c' => ['b' => 'C.B']],
                [['a', 'c'], 'b'], [['a', 'b'], 'A.B'],
            ],

            '#10' => [
                ['a' => ['b' => 'A.B'], 'c' => ['b' => 'C.B']],
                [['c', 'a'], 'b'], [['c', 'b'], 'C.B'],
            ],

            '#11' => [
                ['a' => ['b' => 'A.B'], 'c' => ['b' => 'C.B']],
                [[['c'], 'a'], 'b'], [['c', 'b'], 'C.B'],
            ],

            '#12' => [
                ['a' => ['b' => ['c' => 'A.B.C']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['a', 'd'], 'b', 'c'], [['a', 'b', 'c'], 'A.B.C'],
            ],

            '#13' => [
                ['a' => ['b' => ['c' => 'A.B.C']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['d', 'a'], 'b', 'c'], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#14' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['a', 'd'], 'b', 'c'], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#15' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['a', 'd'], 'b', ['e', 'c']], [['a', 'b', 'e'], 'A.B.E'],
            ],

            '#16' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['d', 'a'], 'b', ['e', 'c']], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#17' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[['a', 'b'], ['d', 'b']], ['e', 'c']], [['a', 'b', 'e'], 'A.B.E'],
            ],

            '#18' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[['d', 'b'], ['a', 'b']], ['e', 'c']], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#19' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['d', 'b', 'c'], ['a', 'b', 'e']], [['d', 'b'], ['c' => 'D.B.C']],
            ],

            '#20' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[['d', 'b', 'c'], ['a', 'b', 'e']]], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#21' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[[['d', 'b', 'c'], ['a', 'b', 'e']]]], [['d', 'b'], ['c' => 'D.B.C']],
            ],

            '#22' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[['d', 'b', 'c']]], [['d', 'b', 'c'], 'D.B.C'],
            ],

            '#23' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [['d', 'b', 'c']], [['d'], ['b' => ['c' => 'D.B.C']]],
            ],

            '#24' => [
                ['a' => ['b' => ['e' => 'A.B.E']], 'd' => ['b' => ['c' => 'D.B.C']]],
                [[[['d', 'b', 'c']]]], [['d'], ['b' => ['c' => 'D.B.C']]],
            ],

            '#25' => [
                ['a' => ['b' => ['c' => 'A.B.C']], 'd' => ['e' => ['c' => 'D.B.C']]],
                ['a', ['b', 'e'], 'c'], [['a', 'b', 'c'], 'A.B.C'],
            ],

            '#26' => [
                ['a' => ['b' => ['c' => 'A.B.C'], 'e' => ['c' => 'A.E.C']]],
                ['a', ['e', 'b'], 'c'], [['a', 'e', 'c'], 'A.E.C'],
            ],

            '#27' => [
                ['a' => ['b' => ['c' => 'A.B.C'], 'e' => ['c' => 'A.E.C']]],
                ['a', ['b', 'e'], 'c'], [['a', 'b', 'c'], 'A.B.C'],
            ],

            '#28' => [
                ['a' => ['b' => ['x' => 'A.B.X'], 'e' => ['c' => 'A.E.C']]],
                ['a', ['e', 'b'], 'c'], [['a', 'e', 'c'], 'A.E.C'],
            ],

            '#29' => [
                ['a' => ['b' => ['c' => 'A.B.C'], 'e' => ['x' => 'A.E.X']]],
                ['a', ['e', 'b'], 'c'], [['a', 'b', 'c'], 'A.B.C'],
            ],

            '#30' => [
                ['a' => ['b' => ['x' => 'A.B.X'], 'e' => ['c' => 'A.E.C']]],
                ['a', ['b', 'e'], 'c'], [['a', 'e', 'c'], 'A.E.C'],
            ],

            '#31' => [
                ['a' => ['b' => ['c' => 'A.B.C'], 'e' => ['x' => 'A.E.X']]],
                ['a', ['b', 'e'], 'c'], [['a', 'b', 'c'], 'A.B.C'],
            ],

            '#98' => [
                ['a' => ['b' => 'A.B']],
                ['a', [null]], [null],
            ],

            '#99' => [
                ['a' => ['b' => 'A.B']],
                ['a', [null, 'b']], [['a', 'b'], 'A.B'],
            ],
        ];
    }

    /**
     * @dataProvider provNestedArrayLookup
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param array<array-key|array> $lookup
     * @psalm-param list{0: mixed, 1?: mixed} $expected
     */
    public function testNestedArrayLookup(array $array, array $lookup, array $expected): void
    {
        if (count($expected) >= 2) {
            $this->assertSame($expected[0], NestedArray::lookup($array, $lookup, $retval));
            $this->assertSame($expected[1], $retval);
        } else {
            $this->assertSame($expected[0], NestedArray::lookup($array, $lookup));
        }
    }
}

<?php

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\NSArray;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\KsortedArrayIdenticalToTrait;


/**
 * @covers \Tailors\Lib\Injector\NSArray
 *
 * @internal
 */
final class NSArrayTest extends TestCase
{
    use ExtendsClassTrait;
    use KsortedArrayIdenticalToTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertExtendsClass(\ArrayObject::class, NSArray::class);
    }

    /**
     * @psalm-return array<list{0:list{0?:iterable}, 1:array}>
     */
    public static function provConstructor(): iterable
    {
        return [
            // #0
            [
                [],
                []
            ],
            // #1
            [
                [[]],
                []
            ],
            // #2
            [
                [
                    [
                        '\\Foo\\Bar' => 'FOO BAR'
                    ]
                ],
                ['foo\\bar' => 'FOO BAR']
            ],
            // #3
            [
                [
                    [
                        '\\Foo\\Bar' => 'FOO BAR',
                        123 => '123',
                        '\\Baz\\Gez' => 'BAZ GEZ',
                    ]
                ],
                [
                    'foo\\bar' => 'FOO BAR',
                    'baz\\gez' => 'BAZ GEZ',
                    123 => '123',
                ]
            ],
            // #4
            [
                [
                    new \ArrayObject([
                        '\\Foo\\Bar' => 'FOO BAR',
                        123 => '123',
                        '\\Baz\\Gez' => 'BAZ GEZ',
                    ])
                ],
                [
                    'foo\\bar' => 'FOO BAR',
                    'baz\\gez' => 'BAZ GEZ',
                    123 => '123',
                ]
            ],
            // #5
            [
                [
                    (function (): \Generator {
                        yield '\\Foo\\Bar' => 'FOO BAR';
                        yield 123 => '123';
                        yield '\\Baz\\Gez' => 'BAZ GEZ';
                    })()
                ],
                [
                    'foo\\bar' => 'FOO BAR',
                    'baz\\gez' => 'BAZ GEZ',
                    123 => '123',
                ]
            ],
        ];
    }

    /**
     * @dataProvider provConstructor
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(array $args, array $expect): void
    {
        $array = new NSArray(...$args);
        $this->assertKsortedArrayIdenticalTo($expect, $array->getArrayCopy());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testOffsetExists(): void
    {
        $array = new NSArray([
            '\\Foo\\Bar' => 'FOO BAR',
            123 => true,
            567 => null,
        ]);

        $this->assertTrue($array->offsetExists('\\Foo\\Bar'));
        $this->assertTrue($array->offsetExists('Foo\\Bar'));
        $this->assertTrue($array->offsetExists('\\foo\\bar'));
        $this->assertTrue($array->offsetExists('foo\\bar'));
        $this->assertTrue($array->offsetExists(123));
        $this->assertTrue($array->offsetExists(567));

        $this->assertFalse($array->offsetExists('inexistent'));
        $this->assertFalse($array->offsetExists(0));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsset(): void
    {
        $array = new NSArray([
            '\\Foo\\Bar' => 'FOO BAR',
            123 => true,
            567 => null,
        ]);

        $this->assertTrue(isset($array['\\Foo\\Bar']));
        $this->assertTrue(isset($array['Foo\\Bar']));
        $this->assertTrue(isset($array['\\foo\\bar']));
        $this->assertTrue(isset($array['foo\\bar']));
        $this->assertTrue(isset($array[123]));
        $this->assertTrue(isset($array[567]));

        $this->assertFalse(isset($array['inexistent']));
        $this->assertFalse(isset($array[0]));
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\KsortedArrayIdenticalToTrait;

/**
 * @template-extends AbstractNormalizedKeyArray<mixed>
 */
final class LowerCaseArrayYP8NW extends AbstractNormalizedKeyArray
{
    /**
     * @psalm-template K
     *
     * @psalm-param K $key
     *
     * @psalm-return (K is string ? lowercase-string : K)
     */
    protected static function normalizeKey(mixed $key): mixed
    {
        if (!is_string($key)) {
            return $key;
        }

        return strtolower($key);
    }
}

/**
 * @covers \Tailors\Lib\Injector\AbstractNormalizedKeyArray
 * @covers \Tailors\Lib\Injector\LowerCaseArrayYP8NW
 *
 * @internal
 *
 * @psalm-suppress InternalClass
 * @psalm-suppress InternalMethod
 */
final class AbstractNormalizedKeyArrayTest extends TestCase
{
    use ExtendsClassTrait;
    use KsortedArrayIdenticalToTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertExtendsClass(\ArrayObject::class, AbstractNormalizedKeyArray::class);
    }

    /**
     * @psalm-return array<list{0:list{0?:array|object,1?:int}, 1:array, 2:int}>
     */
    public static function provConstructor(): iterable
    {
        return [
            // #0
            [
                [],
                [],
                0,
            ],
            // #1
            [
                [[]],
                [],
                0,
            ],
            // #2
            [
                [[], 123],
                [],
                123,
            ],
            // #3
            [
                [
                    [
                        '\\Foo\\Bar' => 'FOO BAR',
                    ],
                ],
                ['\\foo\\bar' => 'FOO BAR'],
                0,
            ],
            // #4
            [
                [
                    [
                        '\\Foo\\Bar' => 'FOO BAR',
                        123          => '123',
                        '\\Baz\\Gez' => 'BAZ GEZ',
                    ],
                ],
                [
                    '\\foo\\bar' => 'FOO BAR',
                    '\\baz\\gez' => 'BAZ GEZ',
                    123          => '123',
                ],
                0,
            ],
            // #5
            [
                [
                    new \ArrayObject([
                        '\\Foo\\Bar' => 'FOO BAR',
                        123          => '123',
                        '\\Baz\\Gez' => 'BAZ GEZ',
                    ]),
                ],
                [
                    '\\foo\\bar' => 'FOO BAR',
                    '\\baz\\gez' => 'BAZ GEZ',
                    123          => '123',
                ],
                0,
            ],
            // #6
            [
                [new class() {}],
                [],
                0,
            ],
        ];
    }

    /**
     * @dataProvider provConstructor
     *
     * @psalm-param list{0?:array|object,1?:int} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(array $args, array $expectArray, int $expectFlags): void
    {
        $array = new LowerCaseArrayYP8NW(...$args);
        $this->assertKsortedArrayIdenticalTo($expectArray, $array->getArrayCopy());
        $this->assertSame($expectFlags, $array->getFlags());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testOffsetExists(): void
    {
        $array = new LowerCaseArrayYP8NW([
            '\\Foo\\Bar' => 'FOO BAR',
            123          => true,
            567          => null,
        ]);

        $this->assertTrue($array->offsetExists('\\Foo\\Bar'));
        $this->assertTrue($array->offsetExists('\\foo\\bar'));
        $this->assertTrue($array->offsetExists(123));
        $this->assertTrue($array->offsetExists(567));

        $this->assertFalse($array->offsetExists('inexistent'));
        $this->assertFalse($array->offsetExists(0));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testOffsetIsset(): void
    {
        $array = new LowerCaseArrayYP8NW([
            '\\Foo\\Bar' => 'FOO BAR',
            123          => true,
            567          => null,
        ]);

        $this->assertTrue($array->offsetIsSet('\\Foo\\Bar'));
        $this->assertTrue($array->offsetIsSet('\\foo\\bar'));
        $this->assertTrue($array->offsetIsSet(123));

        $this->assertFalse($array->offsetIsSet('inexistent'));
        $this->assertFalse($array->offsetIsSet(0));
        $this->assertFalse($array->offsetIsSet(567));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsset(): void
    {
        $array = new LowerCaseArrayYP8NW([
            '\\Foo\\Bar' => 'FOO BAR',
            123          => true,
            567          => null,
        ]);

        $this->assertTrue(isset($array['\\Foo\\Bar']));
        $this->assertTrue(isset($array['\\foo\\bar']));
        $this->assertTrue(isset($array[123]));
        // https://bugs.php.net/bug.php?id=41727
        $this->assertTrue(isset($array[567]));

        $this->assertFalse(isset($array['inexistent']));
        $this->assertFalse(isset($array[0]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testOffsetSet(): void
    {
        $array = new LowerCaseArrayYP8NW();

        $this->assertFalse($array->offsetExists('\\foo\\bar'));
        $array['\\Foo\\Bar'] = 'FOO BAR';
        $this->assertTrue($array->offsetExists('\\foo\\bar'));
        $this->assertSame('FOO BAR', $array['\\foo\\bar']);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testOffsetUnset(): void
    {
        $array = new LowerCaseArrayYP8NW(['\\Foo\\Bar' => 'FOO BAR']);
        $this->assertTrue($array->offsetExists('\\foo\\bar'));
        $this->assertSame('FOO BAR', $array['\\foo\\bar']);
        unset($array['\\Foo\\Bar']);
        $this->assertFalse($array->offsetExists('\\foo\\bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExchangeArrayWithArray(): void
    {
        $array = new LowerCaseArrayYP8NW(['\\Foo\\Bar' => 'FOO BAR']);

        $this->assertKsortedArrayIdenticalTo(
            ['\\foo\\bar' => 'FOO BAR'],
            $array->exchangeArray(['\\Baz\\Gez' => 'BAZ GEZ'])
        );
        $this->assertKsortedArrayIdenticalTo(
            ['\\baz\\gez' => 'BAZ GEZ'],
            $array->getArrayCopy()
        );
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExchangeArrayWithObject(): void
    {
        $array = new LowerCaseArrayYP8NW(['\\Foo\\Bar' => 'FOO BAR']);

        $this->assertKsortedArrayIdenticalTo(['\\foo\\bar' => 'FOO BAR'], $array->exchangeArray(new class() {}));
        $this->assertSame([], $array->getArrayCopy());
    }
}

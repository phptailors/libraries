<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractTwoLevelScopeLookupBase
 * @covers \Tailors\Lib\Injector\FunctionScopeLookup
 * @covers \Tailors\Lib\Injector\TwoLevelLookupTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class FunctionScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsFunctionScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(FunctionScopeLookupInterface::class, FunctionScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame('function', (new FunctionScopeLookup(''))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{string|array<string>}, mixed}>
     */
    public static function provGetScopeLookup(): iterable
    {
        return [
            '\'\''    => [[''], ''],
            '\'foo\'' => [['foo'], 'foo'],
        ];
    }

    /**
     * @dataProvider provGetScopeLookup
     *
     * @psalm-param list{string|array<string>} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(array $args, mixed $expected): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($expected, $lookup->getScopeLookup());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{string|array<string>},
     *      array{function?: array<string,array<string,mixed>>, ...},
     *      string,
     *      bool,
     *      mixed
     *  }>
     */
    public static function provLookup(): iterable
    {
        return [
            // #0
            [
                [''],
                [
                ],
                'foo', false, null,
            ],
            // #1
            [
                ['Foo\\bar'],
                [
                    'function' => [],
                ],
                'foo', false, null,
            ],
            // #2
            [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => ['foo' => 'Foo\\baz::foo'],
                        'Foo\\gez' => ['foo' => 'Foo\\baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            // #3
            [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\bar' => ['bar' => 'Foo\\bar::bar'],
                        'Foo\\baz' => ['foo' => 'Foo\\baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            // #4
            [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => [
                            'foo' => 'Foo\\baz::foo',
                            'bar' => 'Foo\\baz::bar',
                        ],
                        'Foo\\bar' => [
                            'foo' => 'Foo\\bar::foo',
                            'bar' => 'Foo\\bar::bar',
                        ],
                    ],
                ],
                'foo', true, 'Foo\\bar::foo',
            ],
        ];
    }

    /**
     * @dataProvider provLookup
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{function?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookup(array $args, array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($result, $lookup->lookup($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }
}

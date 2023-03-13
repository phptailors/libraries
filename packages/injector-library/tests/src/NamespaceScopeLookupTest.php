<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\NamespaceScopeLookup
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TNamespaceScopeLookup from NamespaceScopeLookupInterface
 */
final class NamespaceScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsNamespaceScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(NamespaceScopeLookupInterface::class, NamespaceScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame(ScopeType::NamespaceScope, (new NamespaceScopeLookup([]))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{TNamespaceScopeLookup}, mixed}>
     */
    public static function provGetScopeLookup(): iterable
    {
        return [
            '\'\''              => [[''], ''],
            '[]'                => [[[]], []],
            '\'foo\''           => [['foo'], 'foo'],
            '[\'foo\',\'bar\']' => [[['foo', 'bar']], ['foo', 'bar']],
        ];
    }

    /**
     * @dataProvider provGetScopeLookup
     *
     * @psalm-param list{TNamespaceScopeLookup} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(array $args, mixed $expected): void
    {
        $lookup = new NamespaceScopeLookup(...$args);
        $this->assertSame($expected, $lookup->getScopeLookup());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{TNamespaceScopeLookup},
     *      array{NamespaceScope?: array<string,array<string,mixed>>, ...},
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
                ['Foo\\Bar'],
                [
                    'NamespaceScope' => [],
                ],
                'foo', false, null,
            ],
            // #2
            [
                ['Foo\\Bar'],
                [
                    'NamespaceScope' => [
                        'Foo\\Baz' => ['foo' => 'Foo\\Baz::foo'],
                        'Foo\\Gez' => ['foo' => 'Foo\\Baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            // #3
            [
                ['Foo\\Bar'],
                [
                    'NamespaceScope' => [
                        'Foo\\Bar' => ['bar' => 'Foo\\Bar::bar'],
                        'Foo\\Baz' => ['foo' => 'Foo\\Baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            // #4
            [
                ['Foo\\Bar'],
                [
                    'NamespaceScope' => [
                        'Foo\\Baz' => [
                            'foo' => 'Foo\\Baz::foo',
                            'bar' => 'Foo\\Baz::bar',
                        ],
                        'Foo\\Bar' => [
                            'foo' => 'Foo\\Bar::foo',
                            'bar' => 'Foo\\Bar::bar',
                        ],
                    ],
                ],
                'foo', true, 'Foo\\Bar::foo',
            ],
            // #5
            [
                [['Foo\\Baz', 'Foo\\Bar']],
                [
                    'NamespaceScope' => [
                        'Foo\\Bar' => [
                            'foo' => 'Foo\\Bar::foo',
                            'bar' => 'Foo\\Bar::bar',
                        ],
                        'Foo\\Baz' => [
                            'foo' => 'Foo\\Baz::foo',
                            'bar' => 'Foo\\Baz::bar',
                        ],
                    ],
                ],
                'foo', true, 'Foo\\Baz::foo',
            ],
            // #6
            [
                [['Foo\\Baz', 'Foo\\Bar']],
                [
                    'NamespaceScope' => [
                        'Foo\\Bar' => [
                            'foo' => 1,
                            'bar' => 2,
                        ],
                        'Foo\\Baz' => [
                            'foo' => 3,
                            'bar' => 4,
                        ],
                    ],
                ],
                'foo', true, 3,
            ],
        ];
    }

    /**
     * @dataProvider provLookup
     *
     * @psalm-param list{TNamespaceScopeLookup} $args
     * @psalm-param array{NamespaceScope?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookup(array $args, array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new NamespaceScopeLookup(...$args);
        $this->assertSame($result, $lookup->lookup($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }
}

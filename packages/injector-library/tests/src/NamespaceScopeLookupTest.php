<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractTwoLevelScopeLookupBase
 * @covers \Tailors\Lib\Injector\NamespaceScopeLookup
 * @covers \Tailors\Lib\Injector\TwoLevelLookupTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
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
        $this->assertSame('namespace', (new NamespaceScopeLookup([]))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{string|array<string>}, mixed}>
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
     * @psalm-param list{string|array<string>} $args
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
     *      list{string|array<string>},
     *      array{namespace?: array<string,array<string,mixed>>, ...},
     *      string,
     *      bool,
     *      mixed
     *  }>
     */
    public static function provLookupScopedArray(): iterable
    {
        return [
            '#00' => [
                [''],
                [
                ],
                'foo', false, null,
            ],
            '#01' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [],
                ],
                'foo', false, null,
            ],
            '#02' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
                        'Foo\\Baz' => ['foo' => 'Foo\\Baz::foo'],
                        'Foo\\Gez' => ['foo' => 'Foo\\Gez::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            '#03' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
                        'Foo\\Bar' => ['bar' => 'Foo\\Bar::bar'],
                        'Foo\\Baz' => ['foo' => 'Foo\\Baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            '#04' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
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
            '#05' => [
                [['Foo\\Baz', 'Foo\\Bar']],
                [
                    'namespace' => [
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
            '#06' => [
                [['Foo\\Baz', 'Foo\\Bar']],
                [
                    'namespace' => [
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
     * @dataProvider provLookupScopedArray
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{namespace?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedArray(array $args, array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new NamespaceScopeLookup(...$args);
        $this->assertSame($result, $lookup->lookupScopedArray($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-return iterable<array-key, list{
     *      list{string|array<string>},
     *      array{namespace?: array<string,class-string-map<T,T>>, ...},
     *      class-string,
     *      mixed
     *  }>
     */
    public static function provLookupScopedInstanceMap(): iterable
    {
        $e1 = new \Exception();
        $e2 = new \Exception();
        $r1 = new \RuntimeException();
        $r2 = new \RuntimeException();

        return [
            '#00' => [
                [''],
                [
                ],
                \Exception::class, null,
            ],
            '#01' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [],
                ],
                \Exception::class, null,
            ],
            '#02' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
                        'Foo\\Baz' => [\Exception::class => $e1],
                        'Foo\\Gez' => [\Exception::class => $e2],
                    ],
                ],
                \Exception::class, null,
            ],
            '#03' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
                        'Foo\\Bar' => [\RuntimeException::class => $r1],
                        'Foo\\Baz' => [\Exception::class => $e1],
                    ],
                ],
                \Exception::class, null,
            ],
            '#04' => [
                ['Foo\\Bar'],
                [
                    'namespace' => [
                        'Foo\\Baz' => [
                            \Exception::class => $e2,
                            \RuntimeException::class => $r2,
                        ],
                        'Foo\\Bar' => [
                            \Exception::class => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                ],
                \Exception::class, $e1,
            ],
            '#05' => [
                [['Foo\\Baz', 'Foo\\Bar']],
                [
                    'namespace' => [
                        'Foo\\Bar' => [
                            \Exception::class => $e1,
                            \RuntimeException::class => $r1,
                        ],
                        'Foo\\Baz' => [
                            \Exception::class => $e2,
                            \RuntimeException::class => $r2,
                        ],
                    ],
                ],
                \Exception::class, $e2,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedInstanceMap
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{namespace?: array<string,class-string-map<T,T>>, ...} $array
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedInstanceMap(array $args, array $array, string $class, mixed $expected): void
    {
        $lookup = new NamespaceScopeLookup(...$args);
        $this->assertSame($expected, $lookup->lookupScopedInstanceMap($array, $class));
    }
}

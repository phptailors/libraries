<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\MethodScopeLookup
 * @covers \Tailors\Lib\Injector\ThreeLevelLookupTrait
 * @covers \Tailors\Lib\Injector\TwoLevelLookupTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class MethodScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsMethodScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(MethodScopeLookupInterface::class, MethodScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame('method', (new MethodScopeLookup(['', '']))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{list{string,string|array<string>}}, mixed}>
     */
    public static function provGetScopeLookup(): iterable
    {
        return [
            '[\'foo\', \'Bar\']'           => [[['foo', 'Bar']], ['foo', 'Bar']],
            '[\'foo\', [\'Bar\',\'Baz\']]' => [[['foo', ['Bar', 'Baz']]], ['foo', ['Bar', 'Baz']]],
        ];
    }

    /**
     * @dataProvider provGetScopeLookup
     *
     * @psalm-param list{list{string,string|array<string>}} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(array $args, mixed $expected): void
    {
        $lookup = new MethodScopeLookup(...$args);
        $this->assertSame($expected, $lookup->getScopeLookup());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{list{string,string|array<string>}},
     *      array{method?: array<string,array<string,array<string,mixed>>>, ...},
     *      string,
     *      bool,
     *      mixed
     *  }>
     */
    public static function provLookupScopedArray(): iterable
    {
        return [
            '#00' => [
                [['', []]],
                [
                ],
                'foo', false, null,
            ],
            '#01' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [],
                ],
                'foo', false, null,
            ],
            '#02' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Baz' => ['foo' => 'Foo\\Baz::m1[foo]'],
                            'Foo\\Gez' => ['foo' => 'Foo\\Gez::m1[foo]'],
                        ],
                    ],
                ],
                'foo', false, null,
            ],
            '#03' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Bar' => ['bar' => 'Foo\\Bar::m1[bar]'],
                            'Foo\\Baz' => ['foo' => 'Foo\\Baz::m1[foo]'],
                        ],
                    ],
                ],
                'foo', false, null,
            ],
            '#04' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Baz' => [
                                'foo' => 'Foo\\Baz::m1[foo]',
                                'bar' => 'Foo\\Baz::m1[bar]',
                            ],
                            'Foo\\Bar' => [
                                'foo' => 'Foo\\Bar::m1[foo]',
                                'bar' => 'Foo\\Bar::m1[bar]',
                            ],
                        ],
                    ],
                ],
                'foo', true, 'Foo\\Bar::m1[foo]',
            ],
            '#05' => [
                [['m2', ['Foo\\Baz', 'Foo\\Bar']]],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Bar' => [
                                'foo' => 'Foo\\Bar::m1[foo]',
                                'bar' => 'Foo\\Bar::m1[bar]',
                            ],
                            'Foo\\Baz' => [
                                'foo' => 'Foo\\Baz::m1[foo]',
                                'bar' => 'Foo\\Baz::m1[bar]',
                            ],
                        ],
                        'm2' => [
                            'Foo\\Bar' => [
                                'foo' => 'Foo\\Bar::m2[foo]',
                                'bar' => 'Foo\\Bar::m2[bar]',
                            ],
                            'Foo\\Baz' => [
                                'foo' => 'Foo\\Baz::m2[foo]',
                                'bar' => 'Foo\\Baz::m2[bar]',
                            ],
                        ],
                    ],
                ],
                'foo', true, 'Foo\\Baz::m2[foo]',
            ],
            '#06' => [
                [['m1', ['Foo\\Baz', 'Foo\\Bar']]],
                [
                    'method' => [
                        'm1' => [
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
                ],
                'foo', true, 3,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedArray
     *
     * @psalm-param list{list{string,string|array<string>}} $args
     * @psalm-param array{method?: array<string,array<string,array<string,mixed>>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedArray(array $args, array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new MethodScopeLookup(...$args);
        $this->assertSame($result, $lookup->lookupScopedArray($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-return iterable<array-key, list{
     *      list{list{string,string|array<string>}},
     *      array{method?: array<string,array<string,class-string-map<T,T>>>, ...},
     *      class-string,
     *      mixed
     *  }>
     */
    public static function provLookupScopedInstanceMap(): iterable
    {
        $e1 = new \Exception();
        $e2 = new \Exception();
        $e3 = new \Exception();
        $e4 = new \Exception();
        $r1 = new \RuntimeException();
        $r2 = new \RuntimeException();
        $r3 = new \RuntimeException();
        $r4 = new \RuntimeException();

        return [
            '#00' => [
                [['', []]],
                [
                ],
                \Exception::class, null,
            ],
            '#01' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [],
                ],
                \Exception::class, null,
            ],
            '#02' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Baz' => [\Exception::class => $e1],
                            'Foo\\Gez' => [\Exception::class => $e2],
                        ],
                    ],
                ],
                \Exception::class, null,
            ],
            '#03' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Bar' => [\RuntimeException::class => $r1],
                            'Foo\\Baz' => [\Exception::class => $e1],
                        ],
                    ],
                ],
                \Exception::class, null,
            ],
            '#04' => [
                [['m1', 'Foo\\Bar']],
                [
                    'method' => [
                        'm1' => [
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
                ],
                \Exception::class, $e1,
            ],
            '#05' => [
                [['m2', ['Foo\\Baz', 'Foo\\Bar']]],
                [
                    'method' => [
                        'm1' => [
                            'Foo\\Bar' => [
                                \Exception::class => $e1,
                                \RuntimeException::class => $r1,
                            ],
                            'Foo\\Baz' => [
                                \Exception::class => $e2,
                                \RuntimeException::class => $r2,
                            ],
                        ],
                        'm2' => [
                            'Foo\\Bar' => [
                                \Exception::class => $e3,
                                \RuntimeException::class => $r3,
                            ],
                            'Foo\\Baz' => [
                                \Exception::class => $e4,
                                \RuntimeException::class => $r4,
                            ],
                        ],
                    ],
                ],
                \Exception::class, $e4,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedInstanceMap
     *
     * @psalm-param list{list{string,string|array<string>}} $args
     * @psalm-param array{method?: array<string,array<string,class-string-map<T,T>>>, ...} $array
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedInstanceMap(array $args, array $array, string $class, mixed $expected): void
    {
        $lookup = new MethodScopeLookup(...$args);
        $this->assertSame($expected, $lookup->lookupScopedInstanceMap($array, $class));
    }
}

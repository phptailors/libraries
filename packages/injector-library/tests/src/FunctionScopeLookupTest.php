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
                ['Foo\\bar'],
                [
                    'function' => [],
                ],
                'foo', false, null,
            ],
            '#02' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => ['foo' => 'Foo\\baz::foo'],
                        'Foo\\gez' => ['foo' => 'Foo\\baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            '#03' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\bar' => ['bar' => 'Foo\\bar::bar'],
                        'Foo\\baz' => ['foo' => 'Foo\\baz::foo'],
                    ],
                ],
                'foo', false, null,
            ],
            '#04' => [
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
     * @dataProvider provLookupScopedArray
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{function?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedArray(array $args, array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($result, $lookup->lookupScopedArray($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @psalm-return iterable<array-key, list{
     *      list{string|array<string>},
     *      array{function?: array<string,class-string-map<T,T>>, ...},
     *      class-string,
     *      bool,
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
                ['Foo\\bar'],
                [
                    'function' => [],
                ],
                \Exception::class, null,
            ],
            '#02' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => [\Exception::class => $e1],
                        'Foo\\gez' => [\Exception::class => $e1],
                    ],
                ],
                \Exception::class, null,
            ],
            '#03' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\bar' => [\RuntimeException::class => $r1],
                        'Foo\\baz' => [\Exception::class => $e1],
                    ],
                ],
                \Exception::class, null,
            ],
            '#04' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => [
                            \Exception::class        => $e2,
                            \RuntimeException::class => $r2,
                        ],
                        'Foo\\bar' => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                ],
                \Exception::class, $e1,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedInstanceMap
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{function?: array<string,class-string-map<T,T>>, ...} $array
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedInstanceMap(array $args, array $array, string $class, mixed $expected): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($expected, $lookup->lookupScopedInstanceMap($array, $class));
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @psalm-return iterable<array-key, list{
     *      list{string|array<string>},
     *      array{function?: array<string,class-string-map<T,FactoryInterface<T>>>, ...},
     *      class-string,
     *      bool,
     *      mixed
     *  }>
     */
    public function provLookupScopedFactoryMap(): iterable
    {
        $e1 = $this->createStub(FactoryInterface::class);
        $e2 = $this->createStub(FactoryInterface::class);
        $r1 = $this->createStub(FactoryInterface::class);
        $r2 = $this->createStub(FactoryInterface::class);

        return [
            '#00' => [
                [''],
                [
                ],
                \Exception::class, null,
            ],
            '#01' => [
                ['Foo\\bar'],
                [
                    'function' => [],
                ],
                \Exception::class, null,
            ],
            '#02' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => [\Exception::class => $e1],
                        'Foo\\gez' => [\Exception::class => $e1],
                    ],
                ],
                \Exception::class, null,
            ],
            '#03' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\bar' => [\RuntimeException::class => $r1],
                        'Foo\\baz' => [\Exception::class => $e1],
                    ],
                ],
                \Exception::class, null,
            ],
            '#04' => [
                ['Foo\\bar'],
                [
                    'function' => [
                        'Foo\\baz' => [
                            \Exception::class        => $e2,
                            \RuntimeException::class => $r2,
                        ],
                        'Foo\\bar' => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                ],
                \Exception::class, $e1,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedFactoryMap
     *
     * @psalm-param list{string|array<string>} $args
     * @psalm-param array{function?: array<string,class-string-map<T,FactoryInterface<T>>>, ...} $array
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedFactoryMap(array $args, array $array, string $class, mixed $expected): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($expected, $lookup->lookupScopedFactoryMap($array, $class));
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Container
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TScopeType "class"|"function"|"global"|"method"|"namespace"
 * @psalm-type TScopePath list{0: TScopeType, 1?: string, 2?:string, 3?: string}
 *
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 * @psalm-type TInstances array{
 *      class?:     array<string,class-string-map<T,T>>,
 *      namespace?: array<string,class-string-map<T,T>>,
 *      function?:  array<string,class-string-map<T,T>>,
 *      method?:    array<string,array<string, class-string-map<T,T>>>,
 *      global?:    class-string-map<T,T>
 * }
 * @psalm-type TFactories array{
 *      class?:     array<string,class-string-map<T,FactoryInterface<T>>>,
 *      namespace?: array<string,class-string-map<T,FactoryInterface<T>>>,
 *      function?:  array<string,class-string-map<T,FactoryInterface<T>>>,
 *      method?:    array<string,array<string, class-string-map<T,FactoryInterface<T>>>>,
 *      global?:    class-string-map<T,FactoryInterface<T>>
 * }
 */
final class ContainerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContainerInterface(): void
    {
        $this->assertImplementsInterface(ContainerInterface::class, Container::class);
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      mixed
     * }>
     */
    public function provGetAliases(): iterable
    {
        /** @psalm-var TAliases */
        $aliases = ['global' => ['a' => 'b']];
        /** @psalm-var TInstances */
        $instances = ['global' => [\Exception::class => new \Exception('e')]];
        /** @psalm-var TFactories */
        $factories = ['global' => [\Exception::class => $this->createStub(FactoryInterface::class)]];
        return [
            '#00' => [[], []],
            '#01' => [[[]], []],
            '#02' => [[$aliases], $aliases],
            '#03' => [[$aliases, $instances], $aliases],
            '#04' => [[$aliases, $instances, $factories], $aliases],
        ];
    }

    /**
     * @dataProvider provGetAliases
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $args
     */
    public function testGetAliases(array $args, mixed $expected): void
    {
        $container = new Container(...$args);
        $this->assertSame($expected, $container->getAliases());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      mixed
     * }>
     */
    public function provGetInstances(): iterable
    {
        /** @psalm-var TAliases */
        $aliases = ['global' => ['a' => 'b']];
        /** @psalm-var TInstances */
        $instances = ['global' => [\Exception::class => new \Exception('e')]];
        /** @psalm-var TFactories */
        $factories = ['global' => [\Exception::class => $this->createStub(FactoryInterface::class)]];
        return [
            '#00' => [[], []],
            '#01' => [[$aliases], []],
            '#02' => [[$aliases, []], []],
            '#03' => [[$aliases, $instances], $instances],
            '#04' => [[$aliases, $instances, $factories], $instances],
        ];
    }

    /**
     * @dataProvider provGetInstances
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $args
     */
    public function testGetInstances(array $args, mixed $expected): void
    {
        $container = new Container(...$args);
        $this->assertSame($expected, $container->getInstances());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      mixed
     * }>
     */
    public function provGetFactories(): iterable
    {
        /** @psalm-var TAliases */
        $aliases = ['global' => ['a' => 'b']];
        /** @psalm-var TInstances */
        $instances = ['global' => [\Exception::class => new \Exception('e')]];
        /** @psalm-var TFactories */
        $factories = ['global' => [\Exception::class => $this->createStub(FactoryInterface::class)]];
        return [
            '#00' => [[], []],
            '#01' => [ [$aliases], []],
            '#02' => [ [$aliases, $instances], []],
            '#03' => [ [$aliases, $instances, []], []],
            '#04' => [ [$aliases, $instances, $factories], $factories],
        ];
    }

    /**
     * @dataProvider provGetFactories
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $args
     */
    public function testGetFactories(array $args, mixed $expected): void
    {
        $container = new Container(...$args);
        $this->assertSame($expected, $container->getFactories());
    }


    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      list{0: string, 1: string, 2?: TScopePath},
     *      mixed
     * }>
     */
    public static function provAccessAlias(): iterable
    {
        return [
            '#00' => [
                [],
                ['A', 'a'],
                ['global' => ['a' => 'A']],
            ],

            '#01' => [
                [],
                ['A', 'a', ['class', 'C']],
                ['class' => ['C' => ['a' => 'A']]],
            ],

            '#02' => [
                [],
                ['A', 'a', ['function', 'F']],
                ['function' => ['F' => ['a' => 'A']]],
            ],

            '#03' => [
                [],
                ['A', 'a', ['global']],
                ['global' => ['a' => 'A']],
            ],

            '#04' => [
                [],
                ['A', 'a', ['method', 'm', 'C']],
                ['method' => ['m' => ['C' => ['a' => 'A']]]],
            ],

            '#05' => [
                [[
                    'global' => ['a' => 'g.A'],
                    'class' => ['X' => ['a' => 'X.a'], 'C' => ['b' => 'C.b']]
                ]],
                ['C.a', 'a', ['class', 'C']],
                [
                    'global' => ['a' => 'g.A'],
                    'class' => ['X' => ['a' => 'X.a'], 'C' => ['b' => 'C.b', 'a' => 'C.a']]
                ],
            ],

            '#06' => [
                [[
                    'global' => ['a' => 'g.A'],
                    'class' => ['X' => ['a' => 'X.a'], 'C' => ['a' => '?']]
                ]],
                ['C.a', 'a', ['class', 'C']],
                [
                    'global' => ['a' => 'g.A'],
                    'class' => ['X' => ['a' => 'X.a'], 'C' => ['a' => 'C.a']]
                ],
            ],
        ];
    }

    /**
     * @dataProvider provAccessAlias
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $ctorArgs
     * @psalm-param list{0: string, 1: string, 2?: TScopePath} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAccessAlias(array $ctorArgs, array $args, mixed $aliases): void
    {
        $container = new Container(...$ctorArgs);
        $container->setAlias(...$args);

        $expected = array_shift($args);

        $this->assertSame($expected,  $container->getAlias(...$args));

        $this->assertSame($aliases, $container->getAliases());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      list{0: object, 1: class-string, 2?: TScopePath},
     *      mixed
     * }>
     */
    public static function provAccessInstance(): iterable
    {
        $e = new \Exception('e');
        $e1 = new \Exception('e1');
        $e2 = new \Exception('e2');
        $e3 = new \Exception('e3');
        $r1 = new \RuntimeException('r1');

        /** @psalm-var array<list{
         *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
         *      list{0: object, 1: class-string, 2?: TScopePath},
         *      mixed
         * }> */
        return [
            '#00' => [
                [],
                [$e1, \Exception::class],
                ['global' => [\Exception::class => $e1]],
            ],

            '#01' => [
                [],
                [$e1, \Exception::class, ['class', 'C']],
                ['class' => ['C' => [\Exception::class => $e1]]],
            ],

            '#02' => [
                [],
                [$e1, \Exception::class, ['function', 'F']],
                ['function' => ['F' => [\Exception::class => $e1]]],
            ],

            '#03' => [
                [],
                [$e1, \Exception::class, ['global']],
                ['global' => [\Exception::class => $e1]],
            ],

            '#04' => [
                [],
                [$e1, \Exception::class, ['method', 'm', 'C']],
                ['method' => ['m' => ['C' => [\Exception::class => $e1]]]],
            ],

            '#05' => [
                [[], [
                    'global' => [\Exception::class => $e1],
                    'class' => [
                        'X' => [\Exception::class => $e2],
                        'C' => [\RuntimeException::class => $r1],
                    ],
                ]],
                [$e3, \Exception::class, ['class', 'C']],
                [
                    'global' => [\Exception::class => $e1],
                    'class' => [
                        'X' => [\Exception::class => $e2],
                        'C' => [\RuntimeException::class => $r1, \Exception::class => $e3]
                    ],
                ],
            ],

            '#06' => [
                [[], [
                    'global' => [\Exception::class => $e1],
                    'class' => ['X' => [\Exception::class => $e2], 'C' => [\Exception::class => $e]],
                ]],
                [$e3, \Exception::class, ['class', 'C']],
                [
                    'global' => [\Exception::class => $e1],
                    'class' => ['X' => [\Exception::class => $e2], 'C' => [\Exception::class => $e3]],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provAccessInstance
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $ctorArgs
     * @psalm-param list{0: object, 1: class-string, 2?: TScopePath} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAccessInstance(array $ctorArgs, array $args, mixed $instances): void
    {
        $container = new Container(...$ctorArgs);
        $container->setInstance(...$args);

        $expected = array_shift($args);

        $this->assertSame($expected,  $container->getInstance(...$args));

        $this->assertSame($instances, $container->getInstances());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
     *      list{0: FactoryInterface<object>, 1: class-string, 2?: TScopePath},
     *      mixed
     * }>
     */
    public function provAccessFactory(): iterable
    {
        $f1 = $this->createStub(FactoryInterface::class);
        $f2 = $this->createStub(FactoryInterface::class);
        $f3 = $this->createStub(FactoryInterface::class);
        $f4 = $this->createStub(FactoryInterface::class);
        $f5 = $this->createStub(FactoryInterface::class);

        /** @psalm-var array<list{
         *      list{0?: TAliases, 1?: TInstances, 2?: TFactories},
         *      list{0: FactoryInterface<object>, 1: class-string, 2?: TScopePath},
         *      mixed
         * }> */
        return [
            '#00' => [
                [],
                [$f2, \Exception::class],
                ['global' => [\Exception::class => $f2]],
            ],

            '#01' => [
                [],
                [$f2, \Exception::class, ['class', 'C']],
                ['class' => ['C' => [\Exception::class => $f2]]],
            ],

            '#02' => [
                [],
                [$f2, \Exception::class, ['function', 'F']],
                ['function' => ['F' => [\Exception::class => $f2]]],
            ],

            '#03' => [
                [],
                [$f2, \Exception::class, ['global']],
                ['global' => [\Exception::class => $f2]],
            ],

            '#04' => [
                [],
                [$f2, \Exception::class, ['method', 'm', 'C']],
                ['method' => ['m' => ['C' => [\Exception::class => $f2]]]],
            ],

            '#05' => [
                [[], [], [
                    'global' => [\Exception::class => $f2],
                    'class' => [
                        'X' => [\Exception::class => $f3],
                        'C' => [\RuntimeException::class => $f5],
                    ],
                ]],
                [$f4, \Exception::class, ['class', 'C']],
                [
                    'global' => [\Exception::class => $f2],
                    'class' => [
                        'X' => [\Exception::class => $f3],
                        'C' => [\RuntimeException::class => $f5, \Exception::class => $f4]
                    ],
                ],
            ],

            '#06' => [
                [[], [], [
                    'global' => [\Exception::class => $f2],
                    'class' => ['X' => [\Exception::class => $f3], 'C' => [\Exception::class => $f1]],
                ]],
                [$f4, \Exception::class, ['class', 'C']],
                [
                    'global' => [\Exception::class => $f2],
                    'class' => ['X' => [\Exception::class => $f3], 'C' => [\Exception::class => $f4]],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provAccessFactory
     *
     * @psalm-param list{0?: TAliases, 1?: TInstances, 2?: TFactories} $ctorArgs
     * @psalm-param list{0: FactoryInterface<object>, 1: class-string, 2?: TScopePath} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAccessFactory(array $ctorArgs, array $args, mixed $factories): void
    {
        $container = new Container(...$ctorArgs);
        $container->setFactory(...$args);

        $expected = array_shift($args);

        $this->assertSame($expected,  $container->getFactory(...$args));

        $this->assertSame($factories, $container->getFactories());
    }

    /**
     * @psalm-return iterable<array-key,list{
     *  TAliases,
     *  ContextInterface,
     *  string,
     *  mixed
     * }>
     */
    public static function provLookupAlias(): iterable
    {
        return [
            'class-#00' => [
                [], new ClassContext(self::class),
                'foo', null,
            ],
            'class-#01' => [
                [
                    'method'    => [],
                    'class'     => [],
                    'namespace' => [],
                    'function'  => [],
                    'global'    => [],
                ],
                new ClassContext(self::class),
                'foo', null,
            ],
            'class-#02' => [
                [
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', 'global:foo',
            ],
            'class-#03' => [
                [
                    'class' => [
                        self::class => [
                            'foo' => self::class.':foo',
                            'bar' => self::class.':bar',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', self::class.':foo',
            ],
            'class-#04' => [
                [
                    'class' => [
                        parent::class => [
                            'foo' => parent::class.':foo',
                            'bar' => parent::class.':bar',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', parent::class.':foo',
            ],
            'class-#05' => [
                [
                    'class' => [
                        parent::class => [
                            'foo' => parent::class.':foo',
                            'bar' => parent::class.':bar',
                        ],
                        self::class => [
                            'foo' => self::class.':foo',
                            'bar' => self::class.':bar',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', self::class.':foo',
            ],
            'class-#06' => [
                [
                    'class' => [
                        parent::class => [
                            'foo' => parent::class.':foo',
                            'bar' => parent::class.':bar',
                        ],
                        self::class => [
                            'foo' => self::class.':foo',
                            'bar' => self::class.':bar',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(parent::class),
                'foo', parent::class.':foo',
            ],
            'class-#07' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            'foo' => 'Tailors\\Lib\\Injector:foo',
                            'bar' => 'Tailors\\Lib\\Injector:bar',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', 'Tailors\\Lib\\Injector:foo',
            ],
            'class-#08' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            'bar' => 'Tailors\\Lib\\Injector:bar',
                        ],
                        'Tailors\\Lib' => [
                            'foo' => 'Tailors\\Lib:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', 'Tailors\\Lib:foo',
            ],
            'class-#09' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            'baz' => 'Tailors\\Lib\\Injector:baz',
                        ],
                        'Tailors\\Lib' => [
                            'bar' => 'Tailors\\Lib:bar',
                        ],
                        'Tailors' => [
                            'foo' => 'Tailors:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'foo', 'Tailors:foo',
            ],
            'class-#10' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            'baz' => 'Tailors\\Lib\\Injector:baz',
                        ],
                        'Tailors\\Lib' => [
                            'bar' => 'Tailors\\Lib:bar',
                        ],
                        'Tailors' => [
                            'foo' => 'Tailors:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new ClassContext(self::class),
                'bar', 'Tailors\\Lib:bar',
            ],
            'method-#01' => [
                [
                    'global' => ['foo' => 'global:foo'],
                ],
                new MethodContext('testLookupAlias', self::class),
                'foo', 'global:foo',
            ],
            'method-#02' => [
                [
                    'namespace' => [
                        'Tailors' => [
                            'foo' => 'Tailors:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new MethodContext('testLookupAlias', self::class),
                'foo', 'Tailors:foo',
            ],
            'method-#03' => [
                [
                    'namespace' => [
                        'Tailors\\Lib' => [
                            'foo' => 'Tailors\\Lib:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new MethodContext('testLookupAlias', self::class),
                'foo', 'Tailors\\Lib:foo',
            ],
            'method-#04' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            'foo' => 'Tailors\\Lib\\Injector:foo',
                        ],
                    ],
                    'global' => ['foo' => 'global:foo'],
                ],
                new MethodContext('testLookupAlias', self::class),
                'foo', 'Tailors\\Lib\\Injector:foo',
            ],
        ];
    }

    /**
     * @dataProvider provLookupAlias
     *
     * @psalm-param TAliases $aliases
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupAlias(array $aliases, ContextInterface $context, string $key, mixed $expected): void
    {
        $container = new Container($aliases);
        $this->assertSame($expected, $container->lookupAlias($key, $context));
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @psalm-return iterable<array-key,list{
     *  TInstances,
     *  ContextInterface,
     *  class-string,
     *  mixed
     * }>
     */
    public static function provLookupInstance(): iterable
    {
        $e1 = new \Exception('e1');
        $e2 = new \Exception('e2');
        $e3 = new \Exception('e3');
        $r1 = new \RuntimeException('r1');
        $r2 = new \RuntimeException('r2');
        $d1 = new \DivisionByZeroError('d1');

        return [
            'class-#00' => [
                [], new ClassContext(self::class),
                self::class, null,
            ],
            'class-#01' => [
                [
                    'method'    => [],
                    'class'     => [],
                    'namespace' => [],
                    'function'  => [],
                    'global'    => [],
                ],
                new ClassContext(self::class),
                self::class, null,
            ],
            'class-#02' => [
                [
                    'global' => [\Exception::class => $e1],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#03' => [
                [
                    'class' => [
                        self::class => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#04' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#05' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                        self::class => [
                            \Exception::class        => $e2,
                            \RuntimeException::class => $r2,
                        ],
                    ],
                    'global' => [\Exception::class => $e3],
                ],
                new ClassContext(self::class),
                \Exception::class, $e2,
            ],
            'class-#06' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                        self::class => [
                            \Exception::class        => $e2,
                            \RuntimeException::class => $r2,
                        ],
                    ],
                    'global' => [\Exception::class => $e3],
                ],
                new ClassContext(parent::class),
                \Exception::class, $e1,
            ],
            'class-#07' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \Exception::class        => $e1,
                            \RuntimeException::class => $r1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#08' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \RuntimeException::class => $r1,
                        ],
                        'Tailors\\Lib' => [
                            \Exception::class => $e2,
                        ],
                    ],
                    'global' => [\Exception::class => $e3],
                ],
                new ClassContext(self::class),
                \Exception::class, $e2,
            ],
            'class-#09' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \DivisionByZeroError::class => $d1,
                        ],
                        'Tailors\\Lib' => [
                            \RuntimeException::class => $r1,
                        ],
                        'Tailors' => [
                            \Exception::class => $e1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#10' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \DivisionByZeroError::class => $d1,
                        ],
                        'Tailors\\Lib' => [
                            \RuntimeException::class => $r1,
                        ],
                        'Tailors' => [
                            \Exception::class => $e1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new ClassContext(self::class),
                \RuntimeException::class, $r1,
            ],
            'method-#01' => [
                [
                    'global' => [\Exception::class => $e1],
                ],
                new MethodContext('testLookupInstance', self::class),
                \Exception::class, $e1,
            ],
            'method-#02' => [
                [
                    'namespace' => [
                        'Tailors' => [
                            \Exception::class => $e1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new MethodContext('testLookupInstance', self::class),
                \Exception::class, $e1,
            ],
            'method-#03' => [
                [
                    'namespace' => [
                        'Tailors\\Lib' => [
                            \Exception::class => $e1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new MethodContext('testLookupInstance', self::class),
                \Exception::class, $e1,
            ],
            'method-#04' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \Exception::class => $e1,
                        ],
                    ],
                    'global' => [\Exception::class => $e2],
                ],
                new MethodContext('testLookupInstance', self::class),
                \Exception::class, $e1,
            ],
        ];
    }

    /**
     * @dataProvider provLookupInstance
     *
     * @psalm-param TInstances $instances
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupInstance(array $instances, ContextInterface $context, string $class, mixed $expected): void
    {
        $container = new Container([], $instances);
        $this->assertSame($expected, $container->lookupInstance($class, $context));
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @psalm-return iterable<array-key,list{
     *  TFactories,
     *  ContextInterface,
     *  class-string,
     *  mixed
     * }>
     */
    public function provLookupFactory(): iterable
    {
        $f1 = $this->createStub(FactoryInterface::class);
        $f2 = $this->createStub(FactoryInterface::class);
        $f3 = $this->createStub(FactoryInterface::class);
        $f4 = $this->createStub(FactoryInterface::class);
        $f5 = $this->createStub(FactoryInterface::class);
        $f6 = $this->createStub(FactoryInterface::class);

        return [
            'class-#00' => [
                [], new ClassContext(self::class),
                self::class, null,
            ],
            'class-#01' => [
                [
                    'method'    => [],
                    'class'     => [],
                    'namespace' => [],
                    'function'  => [],
                    'global'    => [],
                ],
                new ClassContext(self::class),
                self::class, null,
            ],
            'class-#02' => [
                [
                    'global' => [\Exception::class => $f1],
                ],
                new ClassContext(self::class),
                \Exception::class, $f1,
            ],
            'class-#03' => [
                [
                    'class' => [
                        self::class => [
                            \Exception::class        => $f1,
                            \RuntimeException::class => $f4,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new ClassContext(self::class),
                \Exception::class, $f1,
            ],
            'class-#04' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $f1,
                            \RuntimeException::class => $f4,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new ClassContext(self::class),
                \Exception::class, $f1,
            ],
            'class-#05' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $f1,
                            \RuntimeException::class => $f4,
                        ],
                        self::class => [
                            \Exception::class        => $f2,
                            \RuntimeException::class => $f5,
                        ],
                    ],
                    'global' => [\Exception::class => $f3],
                ],
                new ClassContext(self::class),
                \Exception::class, $f2,
            ],
            'class-#06' => [
                [
                    'class' => [
                        parent::class => [
                            \Exception::class        => $f1,
                            \RuntimeException::class => $f4,
                        ],
                        self::class => [
                            \Exception::class        => $f2,
                            \RuntimeException::class => $f5,
                        ],
                    ],
                    'global' => [\Exception::class => $f3],
                ],
                new ClassContext(parent::class),
                \Exception::class, $f1,
            ],
            'class-#07' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \Exception::class        => $f1,
                            \RuntimeException::class => $f4,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new ClassContext(self::class),
                \Exception::class, $f1,
            ],
            'class-#08' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \RuntimeException::class => $f4,
                        ],
                        'Tailors\\Lib' => [
                            \Exception::class => $f2,
                        ],
                    ],
                    'global' => [\Exception::class => $f3],
                ],
                new ClassContext(self::class),
                \Exception::class, $f2,
            ],
            'class-#09' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \DivisionByZeroError::class => $f6,
                        ],
                        'Tailors\\Lib' => [
                            \RuntimeException::class => $f4,
                        ],
                        'Tailors' => [
                            \Exception::class => $f1,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new ClassContext(self::class),
                \Exception::class, $f1,
            ],
            'class-#10' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \DivisionByZeroError::class => $f6,
                        ],
                        'Tailors\\Lib' => [
                            \RuntimeException::class => $f4,
                        ],
                        'Tailors' => [
                            \Exception::class => $f1,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new ClassContext(self::class),
                \RuntimeException::class, $f4,
            ],
            'method-#01' => [
                [
                    'global' => [\Exception::class => $f1],
                ],
                new MethodContext('testLookupFactory', self::class),
                \Exception::class, $f1,
            ],
            'method-#02' => [
                [
                    'namespace' => [
                        'Tailors' => [
                            \Exception::class => $f1,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new MethodContext('testLookupFactory', self::class),
                \Exception::class, $f1,
            ],
            'method-#03' => [
                [
                    'namespace' => [
                        'Tailors\\Lib' => [
                            \Exception::class => $f1,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new MethodContext('testLookupFactory', self::class),
                \Exception::class, $f1,
            ],
            'method-#04' => [
                [
                    'namespace' => [
                        'Tailors\\Lib\\Injector' => [
                            \Exception::class => $f1,
                        ],
                    ],
                    'global' => [\Exception::class => $f2],
                ],
                new MethodContext('testLookupFactory', self::class),
                \Exception::class, $f1,
            ],
        ];
    }

    /**
     * @dataProvider provLookupFactory
     *
     * @psalm-param TFactories $factories
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupFactory(array $factories, ContextInterface $context, string $class, mixed $expected): void
    {
        $container = new Container([], [], $factories);
        $this->assertSame($expected, $container->lookupFactory($class, $context));
    }
}

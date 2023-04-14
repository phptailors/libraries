<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Container
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 *
 * @psalm-type TInstances array{
 *      class?:     array<string,class-string-map<T,T>>,
 *      namespace?: array<string,class-string-map<T,T>>,
 *      function?:  array<string,class-string-map<T,T>>,
 *      method?:    array<string,array<string, class-string-map<T,T>>>,
 *      global?:    class-string-map<T,T>
 * }
 *
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
                    'global' => [ \Exception::class => $e1 ],
                ],
                new ClassContext(self::class),
                \Exception::class, $e1,
            ],
            'class-#03' => [
                [
                    'class' => [
                        self::class => [
                            \Exception::class => $e1,
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
                            \Exception::class => $e1,
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
                            \Exception::class => $e1,
                            \RuntimeException::class => $r1,
                        ],
                        self::class => [
                            \Exception::class => $e2,
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
                            \Exception::class => $e1,
                            \RuntimeException::class => $r1,
                        ],
                        self::class => [
                            \Exception::class => $e2,
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
                            \Exception::class => $e1,
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
}

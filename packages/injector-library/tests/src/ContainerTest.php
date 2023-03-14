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
                'foo', 'Tailors:foo',
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
    public function testLookupAlias(array $aliases, ContextInterface $context, string $key, mixed $result): void
    {
        $container = new Container($aliases);
        $this->assertSame($result, $container->lookupAlias($key, $context));
    }
}

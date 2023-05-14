<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ContextualArray
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TScopeType "class"|"function"|"global"|"method"|"namespace"
 * @psalm-type TScopePath list{0: TScopeType, 1?:string, 2?: string}
 * @psalm-type TItemPath list{0: TScopeType, 1: string,  2?: string, 3?: string}
 */
final class ContextualArrayTest extends TestCase
{
    /**
     * @psalm-return iterable<array-key, list{
     *      list{array, non-empty-list<string>},
     *      array{result: mixed, value?: mixed}
     * }>
     */
    public static function provGet(): iterable
    {
        return [
            '#00' => [
                [],
                ['global', 'x'],
                ['result' => false],
            ],

            '#01' => [
                ['global' => []],
                ['global', 'x'],
                ['result' => false],
            ],

            '#02' => [
                ['global' => ['y' => 'Y']],
                ['global', 'x'],
                ['result' => false],
            ],

            '#03' => [
                ['global' => ['x' => 'X']],
                ['global', 'x'],
                ['result' => true, 'value' => 'X'],
            ],

            '#04' => [
                ['class' => ['Klass' => ['x' => 'X']]],
                ['class', 'x'],
                ['result' => false],
            ],

            '#05' => [
                ['class' => ['Klass' => ['x' => 'X']]],
                ['class', 'Klass'],
                ['result' => false],
            ],

            '#06' => [
                ['class' => ['Klass' => ['x' => 'X']]],
                ['class', 'Klass', 'x'],
                ['result' => true, 'value' => 'X'],
            ],

            '#07' => [
                ['class' => ['Klass' => ['x' => 'X']]],
                ['class', 'Klass', 'x', 'y'],
                ['result' => false],
            ],
        ];
    }

    /**
     * @dataProvider provGet
     *
     * @psalm-param non-empty-list<string> $path
     * @psalm-param array{result: mixed, value?: mixed} $expected
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGet(array $array, array $path, array $expected): void
    {
        $this->assertSame($expected['result'], ContextualArray::get($array, $path, $retval));
        if (array_key_exists('value', $expected)) {
            $this->assertSame($expected['value'], $retval);
        }
    }

    /**
     * @psalm-return iterable<array-key, list{array, list{TItemPath, mixed}, mixed}>
     */
    public static function provSet(): iterable
    {
        return [
            '#00' => [
                [],
                [['class', 'x'], 'X'],
                [],
            ],

            '#01' => [
                [],
                [['function', 'x'], 'X'],
                [],
            ],

            '#02' => [
                [],
                [['global', 'x', 'y'], 'Y'],
                [],
            ],

            '#03' => [
                [],
                [['method', 'x'], 'X'],
                [],
            ],

            '#04' => [
                [],
                [['method', 'x', 'y'], 'Y'],
                [],
            ],

            '#05' => [
                [],
                [['namespace', 'x'], 'X'],
                [],
            ],

            '#06' => [
                [],
                [['class', 'C', 'x', 'y'], 'Y'],
                [],
            ],

            '#07' => [
                [],
                [['function', 'f', 'x', 'y'], 'Y'],
                [],
            ],

            '#08' => [
                [],
                [['global', 'x', 'y'], 'Y'],
                [],
            ],

            '#09' => [
                [],
                [['method', 'm', 'C', 'x', 'Y'], 'Y'],
                [],
            ],

            '#10' => [
                [],
                [['namespace', 'n', 'x', 'y'], 'Y'],
                [],
            ],

            '#11' => [
                [],
                [['class', 'C', 'x'], 'X'],
                [
                    'class' => [
                        'C' => [
                            'x' => 'X',
                        ],
                    ],
                ],
            ],

            '#12' => [
                [],
                [['function', 'f', 'x'], 'X'],
                [
                    'function' => [
                        'f' => [
                            'x' => 'X',
                        ],
                    ],
                ],
            ],

            '#13' => [
                [],
                [['global', 'x'], 'X'],
                [
                    'global' => [
                        'x' => 'X',
                    ],
                ],
            ],

            '#14' => [
                [],
                [['method', 'm', 'C', 'x'], 'X'],
                [
                    'method' => [
                        'm' => [
                            'C' => [
                                'x' => 'X',
                            ],
                        ],
                    ],
                ],
            ],

            '#15' => [
                [],
                [['namespace', 'n', 'x'], 'X'],
                [
                    'namespace' => [
                        'n' => [
                            'x' => 'X',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provSet
     *
     * @psalm-param TItemPath $path
     * @psalm-param list{TItemPath, mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSet(array $array, array $args, mixed $expected): void
    {
        ContextualArray::set($array, ...$args);
        $this->assertSame($expected, $array);
    }
}

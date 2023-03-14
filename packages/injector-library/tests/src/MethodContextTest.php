<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\MethodContext
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class MethodContextTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractContextBase(): void
    {
        $this->assertExtendsClass(AbstractContextBase::class, MethodContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextInterface(): void
    {
        $this->assertImplementsInterface(ContextInterface::class, MethodContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testName(): void
    {
        $context = new MethodContext('testName', self::class);
        $this->assertSame('testName', $context->name());
        $this->assertSame(self::class, $context->className());
    }

    /**
     * @psalm-return iterable<array-key, list{list{string,class-string}, array}>
     */
    public static function provGetLookupScopes(): iterable
    {
        $parents = array_values(class_parents(self::class));
        $interfaces = array_values(class_implements(self::class));
        sort($interfaces);

        return [
            'Exception::foo' => [
                ['foo', \Exception::class],
                [
                    [
                        'method',
                        [
                            'foo',
                            [
                                \Exception::class,
                                \Stringable::class,
                                \Throwable::class,
                            ],
                        ],
                    ],
                    [
                        'class',
                        [
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global', null],
                ],
            ],
            'RuntimeException::foo' => [
                ['foo', \RuntimeException::class],
                [
                    [
                        'method',
                        [
                            'foo',
                            [
                                \RuntimeException::class,
                                \Exception::class,
                                \Stringable::class,
                                \Throwable::class,
                            ],
                        ],
                    ],
                    [
                        'class',
                        [
                            \RuntimeException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global', null],
                ],
            ],
            'LengthException::foo' => [
                ['foo', \LengthException::class],
                [
                    [
                        'method',
                        [
                            'foo',
                            [
                                \LengthException::class,
                                \LogicException::class,
                                \Exception::class,
                                \Stringable::class,
                                \Throwable::class,
                            ],
                        ],
                    ],
                    [
                        'class',
                        [
                            \LengthException::class,
                            \LogicException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global', null],
                ],
            ],
            self::class.'::foo' => [
                ['foo', self::class],
                [
                    [
                        'method',
                        [
                            'foo',
                            array_merge([self::class], $parents, $interfaces),
                        ],
                    ],
                    [
                        'class',
                        array_merge([self::class], $parents, $interfaces),
                    ],
                    [
                        'namespace',
                        [
                            'Tailors\\Lib\\Injector',
                            'Tailors\\Lib',
                            'Tailors',
                        ],
                    ],
                    ['global', null],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provGetLookupScopes
     *
     * @psalm-param list{string,class-string} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetLookupScopes(array $args, array $expected): void
    {
        $context = new MethodContext(...$args);
        $scopes = array_map(
            fn (ScopeLookupInterface $scope): array => [$scope->getScopeType(), $scope->getScopeLookup()],
            $context->getLookupScopes()
        );
        $this->assertSame($expected, $scopes);
    }
}

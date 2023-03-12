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
                        ScopeType::MethodScope,
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
                        ScopeType::ClassScope,
                        [
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    [ScopeType::GlobalScope, null],
                ],
            ],
            'RuntimeException::foo' => [
                ['foo', \RuntimeException::class],
                [
                    [
                        ScopeType::MethodScope,
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
                        ScopeType::ClassScope,
                        [
                            \RuntimeException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    [ScopeType::GlobalScope, null],
                ],
            ],
            'LengthException::foo' => [
                ['foo', \LengthException::class],
                [
                    [
                        ScopeType::MethodScope,
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
                        ScopeType::ClassScope,
                        [
                            \LengthException::class,
                            \LogicException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    [ScopeType::GlobalScope, null],
                ],
            ],
            self::class.'::foo' => [
                ['foo', self::class],
                [
                    [
                        ScopeType::MethodScope,
                        [
                            'foo',
                            array_merge([self::class], $parents, $interfaces),
                        ],
                    ],
                    [
                        ScopeType::ClassScope,
                        array_merge([self::class], $parents, $interfaces),
                    ],
                    [
                        ScopeType::NamespaceScope,
                        [
                            'Tailors\\Lib\\Injector',
                            'Tailors\\Lib',
                            'Tailors',
                        ],
                    ],
                    [ScopeType::GlobalScope, null],
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

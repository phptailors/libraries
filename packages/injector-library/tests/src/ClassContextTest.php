<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\ClassContext
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ClassContextTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractContextBase(): void
    {
        $this->assertExtendsClass(AbstractContextBase::class, ClassContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextInterface(): void
    {
        $this->assertImplementsInterface(ContextInterface::class, ClassContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testName(): void
    {
        $context = new ClassContext(self::class);
        $this->assertSame(self::class, $context->name());
    }

    /**
     * @psalm-return iterable<array-key, list{class-string, array}>
     */
    public static function provGetLookupScopes(): iterable
    {
        $parents = array_values(class_parents(self::class));
        $interfaces = array_values(class_implements(self::class));
        sort($interfaces);

        return [
            'Exception' => [
                \Exception::class,
                [
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
            'RuntimeException' => [
                \RuntimeException::class,
                [
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
            'LengthException' => [
                \LengthException::class,
                [
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
            self::class => [
                self::class,
                [
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
     * @psalm-param class-string $name
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetLookupScopes(string $name, array $expected): void
    {
        $context = new ClassContext($name);
        $scopes = array_map(
            fn (ScopeLookupInterface $scope): array => [$scope->getScopeType(), $scope->getScopeLookup()],
            $context->getLookupScopes()
        );
        $this->assertSame($expected, $scopes);
    }
}

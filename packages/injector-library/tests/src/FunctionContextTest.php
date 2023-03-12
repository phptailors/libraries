<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\FunctionContext
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class FunctionContextTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractContextBase(): void
    {
        $this->assertExtendsClass(AbstractContextBase::class, FunctionContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextInterface(): void
    {
        $this->assertImplementsInterface(ContextInterface::class, FunctionContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testName(): void
    {
        $context = new FunctionContext('Foo\\Bar');
        $this->assertSame('Foo\\Bar', $context->name());
    }

    /**
     * @psalm-return iterable<array-key, list{string, array}>
     */
    public static function provGetLookupScopes(): iterable
    {
        return [
            'foo' => [
                'foo',
                [
                    [ScopeType::FunctionScope, 'foo'],
                    [ScopeType::GlobalScope, null],
                ],
            ],
            'Foo\\bar' => [
                'Foo\\bar',
                [
                    [ScopeType::FunctionScope, 'Foo\\bar'],
                    [ScopeType::NamespaceScope, ['Foo']],
                    [ScopeType::GlobalScope, null],
                ],
            ],
            'Foo\\Bar\\baz' => [
                'Foo\\Bar\\baz',
                [
                    [ScopeType::FunctionScope, 'Foo\\Bar\\baz'],
                    [ScopeType::NamespaceScope, ['Foo\\Bar', 'Foo']],
                    [ScopeType::GlobalScope, null],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provGetLookupScopes
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetLookupScopes(string $name, array $expected): void
    {
        $context = new FunctionContext($name);
        $scopes = array_map(
            fn (ScopeLookupInterface $scope): array => [$scope->getScopeType(), $scope->getScopeLookup()],
            $context->getLookupScopes()
        );
        $this->assertSame($expected, $scopes);
    }
}

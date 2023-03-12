<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\FunctionScopeLookup
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TFunctionScopeLookup from FunctionScopeLookupInterface
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
        $this->assertSame(ScopeType::FunctionScope, (new FunctionScopeLookup(''))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{TFunctionScopeLookup}, mixed}>
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
     * @psalm-param list{TFunctionScopeLookup} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(array $args, mixed $expected): void
    {
        $lookup = new FunctionScopeLookup(...$args);
        $this->assertSame($expected, $lookup->getScopeLookup());
    }
}

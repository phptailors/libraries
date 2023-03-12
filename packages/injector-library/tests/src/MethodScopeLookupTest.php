<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\MethodScopeLookup
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TMethodScopeLookup from MethodScopeLookupInterface
 */
final class MethodScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsMethodScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(MethodScopeLookupInterface::class, MethodScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame(ScopeType::MethodScope, (new MethodScopeLookup(['', '']))->getScopeType());
    }

    /**
     * @psalm-return iterable<array-key, list{list{TMethodScopeLookup}, mixed}>
     */
    public static function provGetScopeLookup(): iterable
    {
        return [
            '[\'foo\', \'Bar\']'           => [[['foo', 'Bar']], ['foo', 'Bar']],
            '[\'foo\', [\'Bar\',\'Baz\']]' => [[['foo', ['Bar', 'Baz']]], ['foo', ['Bar', 'Baz']]],
        ];
    }

    /**
     * @dataProvider provGetScopeLookup
     *
     * @psalm-param list{TMethodScopeLookup} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(array $args, mixed $expected): void
    {
        $lookup = new MethodScopeLookup(...$args);
        $this->assertSame($expected, $lookup->getScopeLookup());
    }
}

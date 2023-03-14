<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractOneLevelScopeLookupBase
 * @covers \Tailors\Lib\Injector\GlobalScopeLookup
 * @covers \Tailors\Lib\Injector\OneLevelLookupTrait
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class GlobalScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractOneLevelScopeLookupBase(): void
    {
        $this->assertExtendsClass(AbstractOneLevelScopeLookupBase::class, GlobalScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsGlobalScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(GlobalScopeLookupInterface::class, GlobalScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame('global', (new GlobalScopeLookup())->getScopeType());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(): void
    {
        $lookup = new GlobalScopeLookup();
        $this->assertNull($lookup->getScopeLookup());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      array{global?: array<string,mixed>, ...},
     *      string,
     *      bool,
     *      mixed
     *  }>
     */
    public static function provLookup(): iterable
    {
        return [
            // #0
            [
                [
                ],
                'foo', false, null,
            ],
            // #1
            [
                [
                    'global' => [],
                ],
                'foo', false, null,
            ],
            // #2
            [
                [
                    'global' => [
                        'bar' => 'BAR',
                    ],
                ],
                'foo', false, null,
            ],
            // #3
            [
                [
                    'global' => [
                        'foo' => 'FOO',
                        'bar' => 'BAR',
                    ],
                ],
                'foo', true, 'FOO',
            ],
        ];
    }

    /**
     * @dataProvider provLookup
     *
     * @psalm-param array{function?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookup(array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new GlobalScopeLookup();
        $this->assertSame($result, $lookup->lookup($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }
}

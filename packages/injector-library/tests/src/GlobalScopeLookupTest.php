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
    public static function provLookupScopedArray(): iterable
    {
        return [
            '#00' => [
                [
                ],
                'foo', false, null,
            ],
            '#01' => [
                [
                    'global' => [],
                ],
                'foo', false, null,
            ],
            '#02' => [
                [
                    'global' => [
                        'bar' => 'BAR',
                    ],
                ],
                'foo', false, null,
            ],
            '#03' => [
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
     * @dataProvider provLookupScopedArray
     *
     * @psalm-param array{function?: array<string,array<string,mixed>>, ...} $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedArray(array $array, string $key, bool $result, mixed $value): void
    {
        $lookup = new GlobalScopeLookup();
        $this->assertSame($result, $lookup->lookupScopedArray($array, $key, $retval));
        if ($result) {
            $this->assertSame($value, $retval);
        }
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @psalm-return iterable<array-key, list{
     *      array{global?: class-string-map<T,T>, ...},
     *      class-string,
     *      mixed
     *  }>
     */
    public static function provLookupScopedInstanceMap(): iterable
    {
        $e1 = new \Exception();
        $r1 = new \RuntimeException();

        return [
            '#00' => [
                [
                ],
                \Exception::class, null,
            ],
            '#01' => [
                [
                    'global' => [],
                ],
                \Exception::class, null,
            ],
            '#02' => [
                [
                    'global' => [
                        \RuntimeException::class => $r1,
                    ],
                ],
                \Exception::class, null,
            ],
            '#03' => [
                [
                    'global' => [
                        \Exception::class => $e1,
                        \RuntimeException::class => $r1,
                    ],
                ],
                \Exception::class, $e1,
            ],
        ];
    }

    /**
     * @dataProvider provLookupScopedInstanceMap
     *
     * @psalm-param array{function?: array<string,class-string-map<T,T>>, ...} $array
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupScopedInstanceMap(array $array, string $class, mixed $expected): void
    {
        $lookup = new GlobalScopeLookup();
        $this->assertSame($expected, $lookup->lookupScopedInstanceMap($array, $class));
    }
}

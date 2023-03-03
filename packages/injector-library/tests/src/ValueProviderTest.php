<?php

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @internal This class is not covered by backward compatiblity promise
 * @covers \Tailors\Lib\Injector\ValueProvider
 */
final class ValueProviderTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueProviderInterface(): void
    {
        $this->assertImplementsInterface(ValueProviderInterface::class, ValueProvider::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueMutatorInterface(): void
    {
        $this->assertImplementsInterface(ValueMutatorInterface::class, ValueProvider::class);
    }

    /**
     * @psalm-return iterable<
     *      array-key,
     *      list{
     *          list{0?:mixed,1?:array<string,array<string,scalar>>},
     *          mixed,
     *          array<string,array<string,scalar>>
     *      }
     *  >
     */
    public static function provConstructor(): iterable
    {
        return [
            [
                [],
                null,
                []
            ],
            [
                ['xxx'],
                'xxx',
                []
            ],
            [
                ['xxx', [
                    'namespace' => ['Foo\\Bar' => 'xxx-in-namespace-foo-bar'],
                    'class' => ['Foo\\Bar' => 'xxx-in-class-foo-bar']
                ]],
                'xxx',
                [
                    'namespace' => ['Foo\\Bar' => 'xxx-in-namespace-foo-bar'],
                    'class' => ['Foo\\Bar' => 'xxx-in-class-foo-bar'],
                ]
            ],
        ];
    }

    /**
     * @dataProvider provConstructor
     *
     * @psalm-param list{0?:mixed,1?:array<string,array<string,scalar>>} $args
     * @psalm-param array<string,array<string,scalar>> $scoped
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(array $args, mixed $global, array $scoped): void
    {
        $provider = new ValueProvider(...$args);
        $this->assertSame($global, $provider->getGlobalValue());

        foreach($scoped as $scopeType => $scopeArray) {
            foreach ($scopeArray as $scopeName => $scopedValue) {
                $this->assertTrue($provider->hasScopedValue($scopeType, $scopeName));
                $this->assertSame($scopedValue, $provider->getScopedValue($scopeType, $scopeName));
            }
        }
    }


    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGlobalValue(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();
        $this->assertNull($provider->getGlobalValue());
        $provider->setGlobalValue('bar');
        $this->assertSame('bar', $provider->getGlobalValue());
        $provider->unsetGlobalValue();
        $this->assertNull($provider->getGlobalValue());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testScopedValue(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();
    }
}

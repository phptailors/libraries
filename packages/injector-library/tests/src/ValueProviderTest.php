<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @internal This class is not covered by backward compatiblity promise
 *
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
    public function testImplementsValueAccessorInterface(): void
    {
        $this->assertImplementsInterface(ValueAccessorInterface::class, ValueProvider::class);
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
     *      array-key, list{
     *          list{0?:array<string,array<string,scalar>>},
     *          array<string,array<string,scalar>>,
     *          bool,
     *          mixed
     *      }
     *  >
     */
    public static function provValueProvider(): iterable
    {
        return [
            // #0
            [
                [],
                [],
                false,
                null,
            ],

            // #1
            [
                [['' => ['' => 'global']]],
                ['' => ['' => 'global']],
                true,
                'global',
            ],

            // #2
            [
                [[
                    ''  => ['' => 'global'],
                    'N' => ['Foo\\Bar' => 'in-namespace-foo-bar'],
                    'C' => ['Foo\\Bar' => 'in-class-foo-bar'],
                ]],
                [
                    ''  => ['' => 'global'],
                    'N' => ['Foo\\Bar' => 'in-namespace-foo-bar'],
                    'C' => ['Foo\\Bar' => 'in-class-foo-bar'],
                ],
                true,
                'global',
            ],

            // #3
            [
                [[
                    ''  => ['' => 0],
                    'N' => ['Foo\\Bar' => 1],
                    'C' => ['Foo\\Bar' => 2],
                ]],
                [
                    ''  => ['' => 0],
                    'N' => ['Foo\\Bar' => 1],
                    'C' => ['Foo\\Bar' => 2],
                ],
                true,
                0,
            ],
        ];
    }

    /**
     * @dataProvider provValueProvider
     *
     * @psalm-param list{0?:array<string,array<string,scalar>>} $args
     * @psalm-param array<string,array<string,scalar>> $scoped
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testValueProvider(array $args, array $scoped, bool $hasGlobal, mixed $global): void
    {
        $provider = new ValueProvider(...$args);

        $this->assertSame($hasGlobal, $provider->hasValue());
        if ($hasGlobal) {
            $this->assertSame($global, $provider->getValue());
        }

        $this->assertSame(array_keys($scoped), $provider->getScopeTypes());

        foreach ($scoped as $scopeType => $scopeArray) {
            $this->assertSame(array_keys($scopeArray), $provider->getScopeNames($scopeType));
            foreach ($scopeArray as $scopeName => $scopedValue) {
                $this->assertTrue($provider->hasValue($scopeType, $scopeName));
                $this->assertSame($scopedValue, $provider->getValue($scopeType, $scopeName));
            }
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSetUnsetValue(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();

        // global value
        $this->assertFalse($provider->hasValue());
        $provider->setValue('GLOBAL');
        $this->assertSame('GLOBAL', $provider->getValue());
        $provider->unsetValue();
        $this->assertFalse($provider->hasValue());

        // unsetting twice is harmless
        $provider->unsetValue();

        // scoped value
        $this->assertFalse($provider->hasValue('foo', 'bar'));
        $this->assertFalse($provider->hasValue('foo', 'baz'));

        $provider->setValue('BAR', 'foo', 'bar');
        $provider->setValue('BAZ', 'foo', 'baz');
        $this->assertTrue($provider->hasValue('foo', 'bar'));
        $this->assertTrue($provider->hasValue('foo', 'baz'));
        $this->assertSame('BAR', $provider->getValue('foo', 'bar'));
        $this->assertSame('BAZ', $provider->getValue('foo', 'baz'));

        $provider->unsetValue('foo', 'bar');
        $this->assertFalse($provider->hasValue('foo', 'bar'));
        $this->assertTrue($provider->hasValue('foo', 'baz'));
        $this->assertTrue($provider->hasScopeType('foo'));

        $provider->unsetValue('foo', 'baz');
        $this->assertFalse($provider->hasValue('foo', 'baz'));
        $this->assertFalse($provider->hasScopeType('foo'));

        // unsetting inexistent value is harmless
        $provider->unsetValue('in', 'existent');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnsetValues(): void
    {
        $provider = new ValueProvider([
            'foo' => ['bar' => 'FOO BAR', 'baz' => 'FOO BAZ'],
            'gez' => ['bar' => 'GEZ BAR', 'baz' => 'GEZ BAZ'],
        ]);

        $provider->unsetValues('foo');
        $this->assertFalse($provider->hasScopeType('foo'));
        $this->assertTrue($provider->hasScopeType('gez'));

        // unsetting inexistent scope type is harmless
        $provider->unsetValues('inexistent');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeNamesThrowsUndefinedScopeTypeException(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();

        $this->expectException(UndefinedScopeTypeException::class);
        $this->expectExceptionMessage(sprintf(
            'Value not defined for any scope of type %s',
            var_export('inexistent', true)
        ));

        $provider->getScopeNames('inexistent');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetValuesThrowsUndefinedScopeTypeException(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();

        $this->expectException(UndefinedScopeTypeException::class);
        $this->expectExceptionMessage(sprintf(
            'Value not defined for any scope of type %s',
            var_export('inexistent', true)
        ));

        $provider->getValues('inexistent');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetValueThrowsUndefinedScopeTypeException(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider();

        $this->expectException(UndefinedScopeTypeException::class);
        $this->expectExceptionMessage(sprintf(
            'Value not defined for any scope of type %s',
            var_export('inexistent', true)
        ));

        $provider->getValue('inexistent', 'foo');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetValueThrowsUndefinedScopeException(): void
    {
        /** @psalm-var ValueProvider<string> */
        $provider = new ValueProvider(['N' => ['Baz' => 'BAZ']]);

        $this->expectException(UndefinedScopeException::class);
        $this->expectExceptionMessage(sprintf(
            'Value not defined for scope %s of type %s',
            var_export('Foo\\Bar', true),
            var_export('N', true)
        ));

        $provider->getValue('N', 'Foo\\Bar');
    }

    /**
     * @psalm-return iterable<
     *      array-key, list{
     *          array<string,array<string,scalar>>,
     *          array<list{string,string|array<string>}>,
     *          mixed
     *      }
     * >
     */
    public static function provLookup(): iterable
    {
        return [
            // #0
            [
                [
                    '' => ['' => 'GLOBAL'],
                ],
                [
                    ['', ''],
                ],
                'GLOBAL',
            ],

            // #1
            [
                [
                    '' => ['' => 'GLOBAL'],
                ],
                [
                    ['N', 'Foo'],
                    ['C', 'Foo'],
                    ['', ''],
                ],
                'GLOBAL',
            ],

            // #2
            [
                [
                    ''  => ['' => 'GLOBAL'],
                    'N' => ['Foo' => 'Namespace Foo'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['C', 'Bar'],
                    ['N', 'Bar'],
                    ['', ''],
                ],
                'GLOBAL',
            ],

            // #3
            [
                [
                    ''  => ['' => 'GLOBAL'],
                    'N' => ['Foo' => 'Namespace Foo'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['N', 'Foo'],
                    ['C', 'Foo'],
                    ['', ''],
                ],
                'Namespace Foo',
            ],

            // #4
            [
                [
                    ''  => ['' => 'GLOBAL'],
                    'N' => ['Foo' => 'Namespace Foo'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['C', 'Foo'],
                    ['N', 'Foo'],
                    ['', ''],
                ],
                'Class Foo',
            ],

            // #5
            [
                [
                    ''  => ['' => 'GLOBAL'],
                    'N' => ['Foo' => 'Namespace Foo', 'Bar' => 'Namespace Bar'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['N', ['Bar', 'Foo']],
                    ['', ''],
                ],
                'Namespace Bar',
            ],
        ];
    }

    /**
     * @dataProvider provLookup
     *
     * @psalm-param array<string,array<string,scalar>> $values
     * @psalm-param array<list{string,string|array<string>}> $scopes
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookup(array $values, array $scopes, mixed $expected): void
    {
        $provider = new ValueProvider($values);
        $this->assertSame($expected, $provider->lookup($scopes));
    }

    /**
     * @psalm-return iterable<
     *      array-key, list{
     *          array<string,array<string,scalar>>,
     *          array<list{string,string|array<string>}>,
     *          string
     *      }
     * >
     */
    public function provLookupThrowsUndefinedValue(): iterable
    {
        return [
            // #0
            [
                [],
                [
                    ['', ''],
                ],
                'Value not defined for any of the scopes: \'\': [\'\']',
            ],

            // #1
            [
                [],
                [
                    ['N', 'Foo'],
                    ['C', 'Bar'],
                    ['', ''],
                ],
                'Value not defined for any of the scopes: '.
                '\'namespace\': [\'Foo\'], \'class\': [\'Bar\'], \'\': [\'\']',
            ],

            // #2
            [
                [
                    'N' => ['Bar' => 'Namespace Bar'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['N', 'Foo'],
                    ['C', 'Bar'],
                    ['', ''],
                ],
                'Value not defined for any of the scopes: '.
                '\'namespace\': [\'Foo\'], \'class\': [\'Bar\'], \'\': [\'\']',
            ],

            // #3
            [
                [
                    'N' => ['Bar' => 'Namespace Bar'],
                    'C' => ['Foo' => 'Class Foo'],
                ],
                [
                    ['N', ['Foo', 'Baz']],
                    ['C', 'Bar'],
                    ['', ''],
                ],
                'Value not defined for any of the scopes: '.
                '\'namespace\': [\'Foo\', \'Baz\'], \'class\': [\'Bar\'], \'\': [\'\']',
            ],
        ];
    }

    /**
     * @dataProvider provLookupThrowsUndefinedValue
     *
     * @psalm-param array<string,array<string,scalar>> $values
     * @psalm-param array<list{string,string|array<string>}> $scopes
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLookupThrowsUndefinedValue(array $values, array $scopes, string $message): void
    {
        $provider = new ValueProvider($values);

        $this->expectException(UndefinedValueException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($provider->lookup($scopes));
    }
}

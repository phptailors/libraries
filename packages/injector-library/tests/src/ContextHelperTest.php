<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ContextHelper
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContextHelperTest extends TestCase
{
    /**
     * @psalm-return iterable<array-key, list{string,string}>
     */
    public static function provGetNamespaceOf(): iterable
    {
        return [
            ''                => ['', ''],
            '\\'              => ['\\', ''],
            'foo'             => ['foo', ''],
            '\\foo'           => ['\\foo', ''],
            'Foo\\bar'        => ['Foo\\bar', 'Foo'],
            'Foo\\Bar\\baz'   => ['Foo\\Bar\\baz', 'Foo\\Bar'],
            'Foo\\\\Bar\\baz' => ['Foo\\\\Bar\\baz', 'Foo\\Bar'],
        ];
    }

    /**
     * @dataProvider provGetNamespaceOf
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetNamespaceOf(string $name, string $namespace): void
    {
        $this->assertSame($namespace, ContextHelper::getNamespaceOf($name));
    }

    /**
     * @psalm-return iterable<array-key, list{string,array<string>}>
     */
    public static function provGetNamespaceLookupArray(): iterable
    {
        return [
            ''              => ['', []],
            '\\'            => ['\\', []],
            'Foo'           => ['Foo', ['Foo']],
            'Foo\\'         => ['Foo\\', ['Foo']],
            '\\Foo'         => ['\\Foo', ['Foo']],
            'Foo\\Bar'      => ['Foo\\Bar', ['Foo\\Bar', 'Foo']],
            'Foo\\Bar\\Baz' => ['Foo\\Bar\\Baz', ['Foo\\Bar\\Baz', 'Foo\\Bar', 'Foo']],
        ];
    }

    /**
     * @dataProvider provGetNamespaceLookupArray
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetNamespaceLookupArray(string $name, array $scopes): void
    {
        $this->assertSame($scopes, ContextHelper::getNamespaceLookupArray($name));
    }

    /**
     * @psalm-return iterable<array-key, list{class-string, array}>
     */
    public static function provGetClassLookupArray(): iterable
    {
        return [
            // #0
            [
                \stdClass::class,
                ['stdClass'],
            ],

            // #1
            [
                \RuntimeException::class,
                [\RuntimeException::class, \Exception::class, \Stringable::class, \Throwable::class],
            ],

            // #2
            [
                \Exception::class,
                [\Exception::class, \Stringable::class, \Throwable::class],
            ],
        ];
    }

    /**
     * @dataProvider provGetClassLookupArray
     *
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetClassLookupArray(string $class, array $scopes): void
    {
        $this->assertSame($scopes, ContextHelper::getClassLookupArray($class));
    }
}

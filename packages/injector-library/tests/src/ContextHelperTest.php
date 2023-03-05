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
     * @psalm-return iterable<array-key, list{class-string, array}>
     */
    public static function provGetClassLookupScopes(): iterable
    {
        return [
            // #0
            [
                \stdClass::class,
                ['stdClass']
            ],

            // #1
            [
                \RuntimeException::class,
                [\RuntimeException::class, \Exception::class, \Throwable::class, \Stringable::class]
            ],
        ];
    }

    /**
     * @dataProvider provGetClassLookupScopes
     *
     * @psalm-param class-string $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetClassLookupScopes(string $class, array $scopes): void
    {
        $this->assertSame($scopes, ContextHelper::getClassLookupScopes($class));
    }
}

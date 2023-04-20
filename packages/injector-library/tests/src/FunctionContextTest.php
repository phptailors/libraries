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
    public static function provGetLookupArray(): iterable
    {
        return [
            'foo' => [
                'foo',
                [
                    ['function', 'foo'],
                    ['global'],
                ],
            ],
            'Foo\\bar' => [
                'Foo\\bar',
                [
                    ['function', 'Foo\\bar'],
                    ['namespace', ['Foo']],
                    ['global'],
                ],
            ],
            'Foo\\Bar\\baz' => [
                'Foo\\Bar\\baz',
                [
                    ['function', 'Foo\\Bar\\baz'],
                    ['namespace', ['Foo\\Bar', 'Foo']],
                    ['global'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provGetLookupArray
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetLookupArray(string $name, array $expected): void
    {
        $context = new FunctionContext($name);
        $this->assertSame($expected, $context->getLookupArray());
    }
}

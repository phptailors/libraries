<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\ClassContext
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ClassContextTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractContextBase(): void
    {
        $this->assertExtendsClass(AbstractContextBase::class, ClassContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextInterface(): void
    {
        $this->assertImplementsInterface(ContextInterface::class, ClassContext::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testName(): void
    {
        $context = new ClassContext(self::class);
        $this->assertSame(self::class, $context->name());
    }

    /**
     * @psalm-return iterable<array-key, list{class-string, array}>
     */
    public static function provGetLookupArray(): iterable
    {
        $parents = array_values(class_parents(self::class));
        $interfaces = array_values(class_implements(self::class));
        sort($interfaces);

        return [
            'Exception' => [
                \Exception::class,
                [
                    [
                        'class',
                        [
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global'],
                ],
            ],
            'RuntimeException' => [
                \RuntimeException::class,
                [
                    [
                        'class',
                        [
                            \RuntimeException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global'],
                ],
            ],
            'LengthException' => [
                \LengthException::class,
                [
                    [
                        'class',
                        [
                            \LengthException::class,
                            \LogicException::class,
                            \Exception::class,
                            \Stringable::class,
                            \Throwable::class,
                        ],
                    ],
                    ['global'],
                ],
            ],
            self::class => [
                self::class,
                [
                    [
                        'class',
                        array_merge([self::class], $parents, $interfaces),
                    ],
                    [
                        'namespace',
                        [
                            'Tailors\\Lib\\Injector',
                            'Tailors\\Lib',
                            'Tailors',
                        ],
                    ],
                    ['global'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provGetLookupArray
     *
     * @psalm-param class-string $name
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetLookupArray(string $name, array $expected): void
    {
        $context = new ClassContext($name);
        $this->assertSame($expected, $context->getLookupArray());
    }
}

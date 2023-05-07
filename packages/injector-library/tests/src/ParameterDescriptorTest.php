<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ParameterDescriptor
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ParameterDescriptorTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsParameterDescriptorInterface(): void
    {
        $this->assertImplementsInterface(ParameterDescriptorInterface::class, ParameterDescriptor::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructorWithoutOptions(): void
    {
        $param = new ParameterDescriptor(123, 'x', 'f');
        $this->assertSame(123, $param->getPosition());
        $this->assertSame('x', $param->getName());
        $this->assertSame('f', $param->getFunction());
        $this->assertNull($param->getClass());
        $this->assertNull($param->getType());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructorWithEmptyOptions(): void
    {
        $param = new ParameterDescriptor(123, 'x', 'f', []);
        $this->assertSame(123, $param->getPosition());
        $this->assertSame('x', $param->getName());
        $this->assertSame('f', $param->getFunction());
        $this->assertNull($param->getClass());
        $this->assertNull($param->getType());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructorWithOptions(): void
    {
        $param = new ParameterDescriptor(123, 'x', 'f', ['class' => self::class, 'type' => 'T']);
        $this->assertSame(123, $param->getPosition());
        $this->assertSame('x', $param->getName());
        $this->assertSame('f', $param->getFunction());
        $this->assertSame(self::class, $param->getClass());
        $this->assertSame('T', $param->getType());
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      \ReflectionParameter,
     *       array{
     *          position: mixed,
     *          name: mixed,
     *          function: mixed,
     *          class?: mixed,
     *          type?: mixed
     *      }
     * }>
     */
    public function provFromReflection(): iterable
    {
        return [
            self::class.'::exampleMethod#00' => [
                new \ReflectionParameter([self::class, 'exampleMethod'], 'int'),
                [
                    'position' => 0,
                    'name'     => 'int',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => 'int',
                ],
            ],
            self::class.'::exampleMethod#01' => [
                new \ReflectionParameter([self::class, 'exampleMethod'], 'string'),
                [
                    'position' => 1,
                    'name'     => 'string',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => 'string',
                ],
            ],
            self::class.'::exampleMethod#02' => [
                new \ReflectionParameter([self::class, 'exampleMethod'], 'exception'),
                [
                    'position' => 2,
                    'name'     => 'exception',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => \Exception::class,
                ],
            ],
            self::class.'::exampleMethod#03' => [
                new \ReflectionParameter([self::class, 'exampleMethod'], 'self'),
                [
                    'position' => 3,
                    'name'     => 'self',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => 'self',
                ],
            ],
            self::class.'::exampleMethod#04' => [
                new \ReflectionParameter([self::class, 'exampleMethod'], 'untyped'),
                [
                    'position' => 4,
                    'name'     => 'untyped',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => null,
                ],
            ],
            self::class.'::exampleMethod#05' => [
                new \ReflectionParameter([$this, 'exampleMethod'], 'self'),
                [
                    'position' => 3,
                    'name'     => 'self',
                    'function' => 'exampleMethod',
                    'class'    => self::class,
                    'type'     => 'self',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provFromReflection
     *
     * @psalm-param array{position: mixed, name: mixed, function: mixed, class?: mixed, type?: mixed} $expected
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromReflection(\ReflectionParameter $reflection, array $expected): void
    {
        $param = ParameterDescriptor::fromReflection($reflection);

        $this->assertSame($expected['position'], $param->getPosition());
        $this->assertSame($expected['name'], $param->getName());
        $this->assertSame($expected['function'], $param->getFunction());
        $this->assertSame($expected['class'] ?? null, $param->getClass());
        $this->assertSame($expected['type'] ?? null, $param->getType());
    }

    /**
     * @psalm-param mixed $untyped
     *
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @param mixed $untyped
     */
    public function exampleMethod(int $int, string $string, \Exception $exception, self $self, $untyped): array
    {
        return [$int, $string, $exception, $self, $untyped];
    }
}

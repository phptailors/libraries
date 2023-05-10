<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class TypeException7ZXT5 extends \TypeError implements TypeExceptionInterface
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\TypeException7ZXT5
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class TypeExceptionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): TypeException7ZXT5
    {
        return new TypeException7ZXT5();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContainerExceptionInterface(): void
    {
        $this->assertImplementsInterface(ContainerExceptionInterface::class, TypeExceptionInterface::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(TypeExceptionInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:

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
final class CircularDependencyExceptionFC4JC extends \Exception implements CircularDependencyExceptionInterface
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\CircularDependencyExceptionFC4JC
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class CircularDependencyExceptionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): CircularDependencyExceptionFC4JC
    {
        return new CircularDependencyExceptionFC4JC();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContainerExceptionInterface(): void
    {
        $this->assertImplementsInterface(ContainerExceptionInterface::class, CircularDependencyExceptionInterface::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(CircularDependencyExceptionInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:

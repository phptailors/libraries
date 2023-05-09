<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface as PsrContainerExceptionInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContainerException9W8Y1 extends \Exception implements ContainerExceptionInterface
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ContainerException9W8Y1
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContainerExceptionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): ContainerException9W8Y1
    {
        return new ContainerException9W8Y1();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsPsrContainerExceptionInterface(): void
    {
        $this->assertImplementsInterface(PsrContainerExceptionInterface::class, ContainerExceptionInterface::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContainerExceptionInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundExceptionInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class NotFoundExceptionJB95H extends \Exception implements NotFoundExceptionInterface
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\NotFoundExceptionJB95H
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class NotFoundExceptionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): NotFoundExceptionJB95H
    {
        return new NotFoundExceptionJB95H();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsPsrNotFoundExceptionInterface(): void
    {
        $this->assertImplementsInterface(PsrNotFoundExceptionInterface::class, NotFoundExceptionInterface::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(NotFoundExceptionInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:

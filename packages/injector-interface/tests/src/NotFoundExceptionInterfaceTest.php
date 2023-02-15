<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class NotFoundExceptionZMYOR extends \Exception implements NotFoundExceptionInterface
{
    use NotFoundExceptionInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\NotFoundExceptionInterfaceTrait
 *
 * @internal
 */
final class NotFoundExceptionInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): NotFoundExceptionZMYOR
    {
        return new NotFoundExceptionZMYOR();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsPsrNotFoundExceptionInterface(): void
    {
        $this->assertImplementsInterface(
            \Psr\Container\NotFoundExceptionInterface::class,
            NotFoundExceptionInterface::class
        );
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyInstance(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(NotFoundExceptionInterface::class, $dummy);
    }
}

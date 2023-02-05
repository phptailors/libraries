<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Error\AbstractErrorHandler;
use Tailors\Lib\Error\ErrorHandlerInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\AbstractErrorHandler
 *
 * @internal
 */
final class AbstractErrorHandlerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsErrorHandlerInterface(): void
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, AbstractErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArguments(): void
    {
        $handler = $this->getMockBuilder(AbstractErrorHandler::class)
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithArgument(): void
    {
        $handler = $this->getMockBuilder(AbstractErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(123, $handler->getErrorTypes());
    }
}

// vim: syntax=php sw=4 ts=4 et:

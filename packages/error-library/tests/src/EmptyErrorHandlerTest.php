<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\EmptyErrorHandler
 *
 * @internal
 */
final class EmptyErrorHandlerTest extends TestCase
{
    use AssertIsSingletonTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsErrorHandlerInterface(): void
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, EmptyErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, EmptyErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetErrorTypes(): void
    {
        $this->assertEquals(E_ALL | E_STRICT, EmptyErrorHandler::getInstance()->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvoke(): void
    {
        $this->assertTrue((EmptyErrorHandler::getInstance())(0, '', 'foo.php', 123));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsSingleton(): void
    {
        $this->assertIsSingleton(EmptyErrorHandler::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:

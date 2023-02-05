<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Error\AbstractManagedErrorHandler;
use Tailors\Lib\Error\ErrorHandler;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\ErrorHandler
 *
 * @internal
 *
 * @psalm-type ErrorHandlerFunc callable(int,string,string,int):bool
 */
final class ErrorHandlerTest extends TestCase
{
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractManagedErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, ErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutErrorTypes(): void
    {
        $func = function (): bool {
            return true;
        };
        $handler = new ErrorHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithErrorTypes(): void
    {
        $func = function (): bool {
            return true;
        };
        $handler = new ErrorHandler($func, 123);

        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvoke(): void
    {
        $called = 0;
        $args = [];

        $handler = new ErrorHandler(
            function (int $severity, string $message, string $file, int $line) use (&$called, &$args): bool {
                assert(is_int($called));
                ++$called;
                $args = func_get_args();

                return true;
            }
        );

        $this->assertTrue($handler(123, 'msg', 'foo.php', 456));
        $this->assertEquals(1, $called);
        $this->assertEquals([123, 'msg', 'foo.php', 456], $args);
    }
}

// vim: syntax=php sw=4 ts=4 et:

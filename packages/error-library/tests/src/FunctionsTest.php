<?php

declare(strict_types=1);

namespace Tailors\Tests\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Error\CallerErrorHandler;

use function Tailors\Lib\Error\callerErrorHandler;

use Tailors\Lib\Error\CallerExceptionErrorHandler;

use function Tailors\Lib\Error\callerExceptionErrorHandler;

use Tailors\Lib\Error\EmptyErrorHandler;

use function Tailors\Lib\Error\emptyErrorHandler;

use Tailors\Lib\Error\ErrorHandler;

use function Tailors\Lib\Error\errorHandler;

use Tailors\Lib\Error\ExceptionErrorHandler;

use function Tailors\Lib\Error\exceptionErrorHandler;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @coversNothing
 */
final class FunctionsTest extends TestCase
{
    /**
     * @covers \Tailors\Lib\Error\emptyErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEmptyErrorHandler(): void
    {
        $this->assertInstanceof(EmptyErrorHandler::class, emptyErrorHandler());
    }

    /**
     * @covers \Tailors\Lib\Error\errorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testErrorHandlerWithCallable(): void
    {
        $func = function (): bool {
            return true;
        };
        $handler = errorHandler($func);
        $this->assertInstanceof(ErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Tailors\Lib\Error\errorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testErrorHandlerWithCallableAndErrorTypes(): void
    {
        $func = function (): bool {
            return true;
        };
        $handler = errorHandler($func, E_USER_ERROR);
        $this->assertInstanceof(ErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());
    }

    /**
     * @covers \Tailors\Lib\Error\exceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExceptionErrorHandlerWithoutArgs(): void
    {
        $handler = exceptionErrorHandler();
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        } catch (\ErrorException $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\exceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExceptionErrorHandlerWithClass(): void
    {
        $handler = exceptionErrorHandler(ExceptionA98DB973::class);
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\exceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExceptionErrorHandlerWithClassAndErrorTypes(): void
    {
        $handler = exceptionErrorHandler(ExceptionA98DB973::class, E_USER_NOTICE);
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\exceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExceptionErrorHandlerWithCallable(): void
    {
        $func = function (int $severity, string $message, string $file, int $line): ExceptionA98DB973 {
            return new ExceptionA98DB973($message, 0, $severity, $file, $line);
        };
        $handler = exceptionErrorHandler($func);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\exceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExceptionErrorHandlerWithCallableAndErrorTypes(): void
    {
        $func = function (int $severity, string $message, string $file, int $line): ExceptionA98DB973 {
            return new ExceptionA98DB973($message, 0, $severity, $file, $line);
        };
        $handler = exceptionErrorHandler($func, E_USER_NOTICE);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\callerErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerErrorHandlerWithCallable(): void
    {
        $func = function (): bool {
            return true;
        };

        $caller_line = __LINE__ + 1;
        $handler = callerErrorHandler($func);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
        $this->assertNotEquals(__FILE__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Tailors\Lib\Error\callerErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerErrorHandlerWithCallableAndDistance(): void
    {
        $func = function (): bool {
            return true;
        };

        $caller_line = __LINE__ + 1;
        $handler = callerErrorHandler($func, 0);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Tailors\Lib\Error\callerErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerErrorHandlerWithCallableDistanceAndErrorTypes(): void
    {
        $func = function (): bool {
            return true;
        };

        $caller_line = __LINE__ + 1;
        $handler = callerErrorHandler($func, 0, E_USER_ERROR);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithCallable(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };

        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler($generator);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertNotEquals(__FILE__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithCallableAndDistance(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };

        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithCallableAndErrorTypes(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };

        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0, 123);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithClass(): void
    {
        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler(ExceptionA98DB973::class);

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);

        $this->assertNotEquals(__FILE__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithClassAndDistance(): void
    {
        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler(ExceptionA98DB973::class, 0);

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);

        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithClassDistanceAndErrorTypes(): void
    {
        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler(\ErrorException::class, 0, E_USER_NOTICE);

        $this->assertInstanceof(CallerExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('boom!');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'boom!', __FILE__, __LINE__]);
        } catch (\ErrorException $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());

            throw $e;
        }
    }

    /**
     * @covers \Tailors\Lib\Error\callerExceptionErrorHandler
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallerExceptionErrorHandlerWithNullAndDistance(): void
    {
        $caller_line = __LINE__ + 1;
        $handler = callerExceptionErrorHandler(null, 0);

        $generator = $handler->getExceptionGenerator();

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);

        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }
}

// vim: syntax=php sw=4 ts=4 et:

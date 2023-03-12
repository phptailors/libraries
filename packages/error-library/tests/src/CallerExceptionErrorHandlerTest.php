<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

use function Tailors\Lib\Context\with;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\CallerExceptionErrorHandler
 *
 * @internal
 *
 * @psalm-type ExceptionGenerator (callable(int,string,string,int):\Exception)
 */
final class CallerExceptionErrorHandlerTest extends TestCase
{
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsExceptionErrorHandler(): void
    {
        $this->assertExtendsClass(ExceptionErrorHandler::class, CallerExceptionErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutDistance(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($generator);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutErrorTypes(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithDistance1(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($generator, 1);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithErrorTypes(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructFromOneLevelRecursion(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(1, $generator, 1, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructFromTwoLevelsRecursion(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeDirect(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromOneLevelRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->invokeRecursive(1, $generator, 1);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());

            /** @psalm-suppress PossiblyUndefinedVariable */
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromTwoLevelsRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->invokeRecursive(2, $generator, 2);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());

            /** @psalm-suppress PossiblyUndefinedVariable */
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromDifferentPlaceThanCreated(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromOneLevelRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->triggerRecursive(1, $generator, 1);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());

            /** @psalm-suppress PossiblyUndefinedVariable */
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromTwoLevelsRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->triggerRecursive(2, $generator, 2);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());

            /** @psalm-suppress PossiblyUndefinedVariable */
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromDifferentPlaceThanCreated(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            /** @psalm-suppress UnusedClosureParam */
            with($handler)(function (CallerExceptionErrorHandler $eh) {
                @trigger_error('test error message', E_USER_ERROR);
            });
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    /**
     * @psalm-param ExceptionGenerator $func
     * @psalm-param array{0?:int,1?:int} $args
     */
    protected function createHandler(callable $func, ...$args): CallerExceptionErrorHandler
    {
        return new CallerExceptionErrorHandler($func, ...$args);
    }

    /**
     * @psalm-param ExceptionGenerator $func
     * @psalm-param array{0?:int,1?:int} $args
     */
    protected function createRecursive(int $depth, callable $func, ...$args): CallerExceptionErrorHandler
    {
        if ($depth > 1) {
            return $this->createRecursive($depth - 1, $func, ...$args);
        }

        return new CallerExceptionErrorHandler($func, ...$args);
    }

    /**
     * @throws \Exception
     *
     * @psalm-param ExceptionGenerator $func
     * @psalm-param array{0?:int,1?:int} $args
     *
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    protected function invokeRecursive(int $depth, callable $func, ...$args): bool
    {
        if ($depth > 1) {
            return $this->invokeRecursive($depth - 1, $func, ...$args);
        }
        $handler = new CallerExceptionErrorHandler($func, ...$args);

        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __FILE__, __LINE__]);
    }

    /**
     * @psalm-param ExceptionGenerator $func
     * @psalm-param array{0?:int,1?:int} $args
     *
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    protected function triggerRecursive(int $depth, callable $func, ...$args): string
    {
        if ($depth > 1) {
            return $this->triggerRecursive($depth - 1, $func, ...$args);
        }

        /** @psalm-suppress UnusedClosureParam */
        return with(new CallerExceptionErrorHandler($func, ...$args))(function (CallerExceptionErrorHandler $eh): string {
            @trigger_error('test error message', E_USER_ERROR);
            /** @psalm-suppress UnevaluatedCode */
            return 'test return value';
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:

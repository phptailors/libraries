<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

use function Tailors\Lib\Context\with;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\CallerErrorHandler
 *
 * @internal
 *
 * @psalm-type ErrorHandlerFunction (callable(int,string,string,int):bool)
 */
final class CallerErrorHandlerTest extends TestCase
{
    use ExtendsClassTrait;

    /**
     * @psalm-return ErrorHandlerFunction
     */
    public function handlerCallback1(array &$args = null): callable
    {
        return function (int $severity, string $message, string $file, int $lie) use (&$args): bool {
            $args = func_get_args();

            return true;
        };
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsCustomErrorHandler(): void
    {
        $this->assertExtendsClass(ErrorHandler::class, CallerErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutDistance(): void
    {
        $func = function (): bool {
            return true;
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutErrorTypes(): void
    {
        $func = function (): bool {
            return true;
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($func, 0);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithDistance1(): void
    {
        $func = function (): bool {
            return true;
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($func, [1]);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
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
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($func, 0, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructFromOneLevelRecursion(): void
    {
        $func = function (): bool {
            return true;
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(1, $func, [1, 456]);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructFromTwoLevelsRecursion(): void
    {
        $func = function (): bool {
            return true;
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $func, [2, 456]);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeDirect(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($fcn, 0);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromOneLevelRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertTrue($this->invokeRecursive(1, $fcn, [1]));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromTwoLevelsRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertTrue($this->invokeRecursive(2, $fcn, [2]));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeFromDifferentPlaceThanCreated(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $fcn, [2]);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromOneLevelRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(1, $fcn, [1]));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromTwoLevelsRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(2, $fcn, [2]));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerFromDifferentPlaceThanCreated(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $fcn, [2]);

        /** @psalm-suppress UnusedClosureParam */
        with($handler)(function (CallerErrorHandler $eh) {
            @trigger_error('test error message', E_USER_ERROR);
        });
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    /**
     * @psalm-param ErrorHandlerFunction $func
     * @psalm-param array{0?:int<0,max>,1?:int} $args
     */
    protected function createHandler(callable $func, array $args = []): CallerErrorHandler
    {
        return new CallerErrorHandler($func, ...$args);
    }

    /**
     * @psalm-param ErrorHandlerFunction $func
     * @psalm-param array{0?:int<0,max>,1?:int} $args
     */
    protected function createRecursive(int $depth, callable $func, array $args = []): CallerErrorHandler
    {
        if ($depth > 1) {
            return $this->createRecursive($depth - 1, $func, $args);
        }

        return new CallerErrorHandler($func, ...$args);
    }

    /**
     * @throws \Exception
     *
     * @psalm-param ErrorHandlerFunction $func
     * @psalm-param array{0?:int<0,max>,1?:int} $args
     */
    protected function invokeRecursive(int $depth, callable $func, array $args = []): bool
    {
        if ($depth > 1) {
            return $this->invokeRecursive($depth - 1, $func, $args);
        }
        $handler = new CallerErrorHandler($func, ...$args);

        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __FILE__, __LINE__]);
    }

    /**
     * @psalm-param ErrorHandlerFunction $func
     * @psalm-param array{0?:int<0,max>,1?:int} $args
     */
    protected function triggerRecursive(int $depth, callable $func, array $args = []): string
    {
        if ($depth > 1) {
            return $this->triggerRecursive($depth - 1, $func, $args);
        }

        /** @psalm-suppress UnusedClosureParam */
        return with(new CallerErrorHandler($func, ...$args))(function (CallerErrorHandler $eh): string {
            @trigger_error('test error message', E_USER_ERROR);

            /** @psalm-suppress UnevaluatedCode */
            return 'test return value';
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:

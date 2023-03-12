<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

use function Tailors\Lib\Context\with;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\ExceptionErrorHandler
 *
 * @internal
 *
 * @psalm-type ExceptionGenerator (callable(int,string,string,int):\Exception)
 */
final class ExceptionErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use ExtendsClassTrait;

    /**
     * @psalm-param ExceptionGenerator|string $arg
     * @psalm-param array{0?:int} $tail
     *
     * @throws \InvalidArgumentException
     */
    public function createHandler(callable|string $arg = null, ...$tail): ExceptionErrorHandler
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator($arg);

        return new ExceptionErrorHandler($generator, ...$tail);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractManagedErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, ExceptionErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeExceptionGeneratorWithCallable(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $this->assertSame($generator, ExceptionErrorHandler::makeExceptionGenerator($generator));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeExceptionGeneratorWithClass(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeExceptionGeneratorWithNull(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(null);

        /** @psalm-suppress RedundantConditionGivenDocblockType */
        $this->assertIsCallable($generator);

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeExceptionGeneratorWithWrongArgType(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches(
            '/'.preg_quote(ExceptionErrorHandler::class).'::makeExceptionGenerator\\(\\):'.
            ' Argument #1 \\(\\$arg\\) must be of type callable\\|string\\|null, int given/'
        );

        /** @psalm-suppress InvalidScalarArgument */
        ExceptionErrorHandler::makeExceptionGenerator(123);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeExceptionGeneratorWithNonClassString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(
            '/argument 1 to '.preg_quote(ExceptionErrorHandler::class).
            '::makeExceptionGenerator\\(\\) must be a callable, a class name'.
            ' or null, string given/'
        );

        ExceptionErrorHandler::makeExceptionGenerator('inexistent class');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutErrorTypes(): void
    {
        $generator = function (): \Exception {
            return new \Exception();
        };
        $handler = new ExceptionErrorHandler($generator);
        $this->assertSame($generator, $handler->getExceptionGenerator());
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
        $handler = new ExceptionErrorHandler($generator, 123);

        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWhenSeverityIsRelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');

        call_user_func_array($handler, [E_USER_ERROR, 'foo', 'bar.php', 456]);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWhenSeverityIsIrrelevant(): void
    {
        // The __invoke() itself doesn't filter errors against $errorTypes. It
        // throws its exceptions unconditionally. It's PHP's job to filter
        // errors passed to handler against $errorTypes, because
        //
        //      AbstractManagedExceptionHandler::enterContext()
        //
        // already calls
        //
        //      set_error_handler($self, $errorTypes).
        //
        $handler = $this->createHandler(ExceptionA98DB973::class, E_USER_ERROR);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');

        $this->assertFalse(call_user_func_array($handler, [E_USER_NOTICE, 'foo', 'bar.php', 456]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerWhenSeverityIsRelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class, E_ALL | E_STRICT);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');
        $this->expectExceptionCode(0);

        try {
            /** @psalm-suppress UnusedClosureParam */
            with($handler)(function (ExceptionErrorHandler $eh): void {
                @trigger_error('foo', E_USER_ERROR);
            });
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals(__LINE__ - 5, $e->getLine());

            throw $e;
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testTriggerWhenSeverityIsIrrelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class, E_USER_ERROR);

        /** @psalm-suppress UnusedClosureParam */
        $result = with($handler)(function (ExceptionErrorHandler $eh): string {
            @trigger_error('foo', E_USER_NOTICE);

            return 'test return value';
        });

        $this->assertEquals('test return value', $result);
    }
}

// vim: syntax=php sw=4 ts=4 et:

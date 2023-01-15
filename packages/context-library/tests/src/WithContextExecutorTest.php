<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Context\ExecutorInterface;
use Tailors\Lib\Context\WithContextExecutor;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class ExceptionEB3IB4EL extends \Exception
{
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\WithContextExecutor
 *
 * @internal
 */
final class WithContextExecutorTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsExecutorInterface(): void
    {
        $this->assertImplementsInterface(ExecutorInterface::class, WithContextExecutor::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $foo = $this->createStub(ContextManagerInterface::class);
        $bar = $this->createStub(ContextManagerInterface::class);
        $executor = new WithContextExecutor([$foo, $bar]);
        $this->assertEquals([$foo, $bar], $executor->getContext());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvoke(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $out1 = null;
        $out2 = null;

        $enter = [];
        $exit = [];

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter): mixed {
                    assert(is_array($enter));
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function () use (&$exit): bool {
                    assert(is_array($exit));
                    $exit[] = 'cm1';

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter): mixed {
                    assert(is_array($enter));
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function () use (&$exit): bool {
                    assert(is_array($exit));
                    $exit[] = 'cm2';

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        /** @psalm-var mixed */
        $retval = $executor(
            function (array $arg1, array $arg2) use (&$out1, &$out2): string {
                $out1 = $arg1;
                $out2 = $arg2;

                return 'baz';
            }
        );

        $this->assertEquals('baz', $retval);

        $this->assertSame($in1, $out1);
        $this->assertSame($in2, $out2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     * @psalm-suppress UnusedVariable
     */
    public function testInvokeWithException01(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    assert(is_array($enter));
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    assert(is_array($exit));
                    $exit[] = 'cm1';
                    $ex1 = $exception;

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter) {
                    assert(is_array($enter));
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    assert(is_array($exit));
                    $exit[] = 'cm2';
                    $ex2 = $exception;

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            $executor(
                function () use ($throw): void {
                    throw $throw;
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            $caught = $e;
        }

        $this->assertSame($throw, $caught);

        $this->assertSame($throw, $ex1);
        $this->assertSame($throw, $ex2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWithException02(): void
    {
        $in1 = ['foo'];
        $in2 = ['bar'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    assert(is_array($enter));
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with(null)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    assert(is_array($exit));
                    $exit[] = 'cm1';
                    $ex1 = $exception;

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in2, &$enter) {
                    assert(is_array($enter));
                    $enter[] = 'cm2';

                    return $in2;
                }
            ))
        ;
        $cm2->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    assert(is_array($exit));
                    $exit[] = 'cm2';
                    $ex2 = $exception;

                    return true;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            /** @psalm-var mixed */
            $retval = $executor(
                function () use ($throw): void {
                    throw $throw;
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            /** @psalm-var mixed */
            $retval = false;
            $caught = $e;
        }

        $this->assertNull($retval);
        $this->assertNull($caught);

        $this->assertSame(null, $ex1);
        $this->assertSame($throw, $ex2);

        $this->assertEquals(['cm1', 'cm2'], $enter);
        $this->assertEquals(['cm2', 'cm1'], $exit);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWhenEnterContextThrows(): void
    {
        $in1 = ['foo'];

        $ex1 = null;
        $ex2 = null;

        $enter = [];
        $exit = [];

        $throw = new ExceptionEB3IB4EL('testing exception');

        $cm1 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm1->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($in1, &$enter) {
                    assert(is_array($enter));
                    $enter[] = 'cm1';

                    return $in1;
                }
            ))
        ;
        $cm1->expects($this->once())
            ->method('exitContext')
            ->with($throw)
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex1) {
                    assert(is_array($exit));
                    $exit[] = 'cm1';
                    $ex1 = $exception;

                    return false;
                }
            ))
        ;

        $cm2 = $this->getMockBuilder(ContextManagerInterface::class)
            ->onlyMethods(['enterContext', 'exitContext'])
            ->getMock()
        ;
        $cm2->expects($this->once())
            ->method('enterContext')
            ->will($this->returnCallback(
                function () use ($throw) {
                    throw $throw;
                }
            ))
        ;
        $cm2->expects($this->never())
            ->method('exitContext')
            ->will($this->returnCallback(
                function (\Throwable $exception = null) use (&$exit, &$ex2) {
                    assert(is_array($exit));
                    $exit[] = 'cm2';
                    $ex2 = $exception;

                    return false;
                }
            ))
        ;

        $executor = new WithContextExecutor([$cm1, $cm2]);

        $caught = null;

        try {
            /** @psalm-var mixed */
            $retval = $executor(
                function (): string {
                    return 'ok';
                }
            );
        } catch (ExceptionEB3IB4EL $e) {
            /** @psalm-var mixed */
            $caught = $e;
        }

        $this->assertFalse(isset($retval));
        $this->assertSame($throw, $caught);

        $this->assertSame($throw, $ex1);
        $this->assertNull($ex2);

        $this->assertEquals(['cm1'], $enter);
        $this->assertEquals(['cm1'], $exit);
    }
}

// vim: syntax=php sw=4 ts=4 et:

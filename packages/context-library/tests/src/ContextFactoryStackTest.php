<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextFactoryInterface;
use Tailors\Lib\Context\ContextFactoryStack;
use Tailors\Lib\Context\ContextFactoryStackInterface;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class ContextManager7XGW4 implements ContextManagerInterface
{
    public function enterContext(): self
    {
        return $this;
    }

    public function exitContext(\Throwable $exception = null): bool
    {
        return false;
    }
}

final class ContextFactory7XGW4 implements ContextFactoryInterface
{
    public function getContextManager($arg): ?ContextManagerInterface
    {
        return null;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\ContextFactoryStack
 *
 * @internal
 */
final class ContextFactoryStackTest extends TestCase
{
    use \Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextFactoryStackInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryStackInterface::class, ContextFactoryStack::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, ContextFactoryStack::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsSingletonInterface(): void
    {
        $this->assertImplementsInterface(SingletonInterface::class, ContextFactoryStack::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsSingleton(): void
    {
        $this->assertIsSingleton(ContextFactoryStack::class);
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testBasicStackMethods(): void
    {
        $f0 = $this->getDummyContextFactory();
        $f1 = $this->getDummyContextFactory();

        $stack = ContextFactoryStack::getInstance();

        $this->assertEquals(0, $stack->size());
        $this->assertNull($stack->top());
        $this->assertNull($stack->pop());

        $stack->push($f0);
        $this->assertEquals(1, $stack->size());
        $this->assertSame($f0, $stack->top());
        $this->assertSame($f0, $stack->top()); // indempotent

        $stack->push($f1);
        $this->assertEquals(2, $stack->size());
        $this->assertSame($f1, $stack->top());
        $this->assertSame($f1, $stack->top()); // indempotent

        $this->assertSame($f1, $stack->pop());
        $this->assertEquals(1, $stack->size());

        $this->assertSame($f0, $stack->pop());
        $this->assertEquals(0, $stack->size());

        $stack->push($f0);
        $stack->push($f1);
        $this->assertEquals(2, $stack->size());
        $stack->clean();
        $this->assertEquals(0, $stack->size());
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerOnEmptyStack(): void
    {
        $stack = ContextFactoryStack::getInstance();

        $this->assertNull($stack->getContextManager('an argument'));
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManager(): void
    {
        $cm0 = $this->getDummyContextManager();
        $cm1 = $this->getDummyContextManager();

        $f0 = $this->createMock(ContextFactoryInterface::class);
        $f1 = $this->createMock(ContextFactoryInterface::class);
        $f2 = $this->createMock(ContextFactoryInterface::class);

        $f0->expects($this->exactly(3))
            ->method('getContextManager')
            ->withConsecutive(['foo'], ['foo'], ['baz'])
            ->willReturn($cm0)
        ;
        $f1->expects($this->once())
            ->method('getContextManager')
            ->with('bar')
            ->willReturn($cm1)
        ;
        $f2->expects($this->once())
            ->method('getContextManager')
            ->with('baz')
            ->willReturn(null)
        ;

        $stack = ContextFactoryStack::getInstance();

        $stack->push($f0);
        $this->assertSame($cm0, $stack->getContextManager('foo'));

        $stack->push($f1);
        $this->assertSame($cm1, $stack->getContextManager('bar'));

        $stack->pop();
        $this->assertSame($cm0, $stack->getContextManager('foo'));

        $stack->push($f2);
        $this->assertSame($cm0, $stack->getContextManager('baz'));
    }

    protected function getDummyContextFactory(): ContextFactory7XGW4
    {
        return new ContextFactory7XGW4();
    }

    protected function getDummyContextManager(): ContextManager7XGW4
    {
        return new ContextManager7XGW4();
    }
}

// vim: syntax=php sw=4 ts=4 et:

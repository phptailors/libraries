<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\AbstractManagedContextFactory;
use Tailors\Lib\Context\ContextFactoryInterface;
use Tailors\Lib\Context\ContextFactoryStack;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\AbstractManagedContextFactory
 *
 * @internal
 */
final class AbstractManagedContextFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, AbstractManagedContextFactory::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextManagerInterface(): void
    {
        $interfaces = class_implements(AbstractManagedContextFactory::class);
        $this->assertContains(ContextManagerInterface::class, $interfaces);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEnterContextAndExitContext(): void
    {
        $factory = $this->getMockBuilder(AbstractManagedContextFactory::class)
            ->getMockForAbstractClass()
        ;

        $stack = ContextFactoryStack::getInstance();
        $stack->clean();

        $this->assertSame($factory, $factory->enterContext());

        $this->assertEquals(1, $stack->size());
        $this->assertSame($factory, $stack->top());

        $this->assertFalse($factory->exitContext(null));
        $this->assertEquals(0, $stack->size());
    }
}

// vim: syntax=php sw=4 ts=4 et:

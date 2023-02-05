<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Error\AbstractErrorHandler;
use Tailors\Lib\Error\AbstractManagedErrorHandler;
use Tailors\Lib\Error\ContextManagerTrait;
use Tailors\Lib\Error\ErrorHandlerInterface;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Error\AbstractManagedErrorHandler
 *
 * @internal
 */
final class AbstractManagedErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use UsesTraitTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractErrorHandler::class, AbstractManagedErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsErrorHandlerInterface(): void
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, AbstractManagedErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, AbstractManagedErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesContextManagerTrait(): void
    {
        $this->assertUsesTrait(ContextManagerTrait::class, AbstractManagedErrorHandler::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArguments(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithArgument(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEnterContextt(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;

        $set_error_handler = $this->getFunctionMock('Tailors\Lib\Error', 'set_error_handler');
        $set_error_handler->expects($this->once())->with($handler, 123);

        $this->assertSame($handler, $handler->enterContext());
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExitContextt(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;

        $restore_error_handler = $this->getFunctionMock('Tailors\Lib\Error', 'restore_error_handler');
        $restore_error_handler->expects($this->once());

        $this->assertFalse($handler->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et:

<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextFactoryInterface;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Context\DefaultContextFactory;
use Tailors\Lib\Context\ResourceContextManager;
use Tailors\Lib\Context\TrivialValueWrapper;
use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\Testing\Lib\Context\ExpectFunctionOnceWillReturnTrait;
use Tailors\Testing\Lib\Context\GetContextFunctionMockTrait;
use Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\DefaultContextFactory
 *
 * @internal
 */
final class DefaultContextFactoryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use AssertIsSingletonTrait;
    use GetContextFunctionMockTrait;
    use ExpectFunctionOnceWillReturnTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, DefaultContextFactory::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsSingletonInterface(): void
    {
        $this->assertImplementsInterface(SingletonInterface::class, DefaultContextFactory::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsSingleton(): void
    {
        $this->assertIsSingleton(DefaultContextFactory::class);
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerWithContextManager(): void
    {
        $factory = DefaultContextFactory::getInstance();

        $is_resource = $this->getFunctionMock('Tailors\\Lib\\Context', 'is_resource');

        $is_resource->expects($this->never());

        $cm = $this->createMock(ContextManagerInterface::class);
        $cm->method('enterContext')->willReturn('foo');
        $cm->method('exitContext')->willReturn(false);

        $this->assertSame($cm, $factory->getContextManager($cm));
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerWithResource(): void
    {
        $this->expectFunctionOnceWillReturn('is_resource', ['foo'], true);
        $this->expectFunctionOnceWillReturn('get_resource_type', ['foo'], 'bar');

        $factory = DefaultContextFactory::getInstance();

        $cm = $factory->getContextManager('foo');

        $this->assertInstanceOf(ResourceContextManager::class, $cm);

        /** @psalm-suppress DocblockTypeContradiction */
        $this->assertSame('foo', $cm->getResource());
        $this->assertNull($cm->getDestructor());
    }

    /**
     * @runInSeparateProcess
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetContextManagerWithValue(): void
    {
        $factory = DefaultContextFactory::getInstance();

        $is_resource = $this->getFunctionMock('Tailors\\Lib\\Context', 'is_resource');

        $is_resource->expects($this->once())
            ->with('foo')
            ->willReturn(false)
        ;

        $cm = $factory->getContextManager('foo');

        $this->assertInstanceOf(TrivialValueWrapper::class, $cm);
        $this->assertEquals('foo', $cm->getValue());
    }
}

// vim: syntax=php sw=4 ts=4 et:

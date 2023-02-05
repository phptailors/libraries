<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\InjectorContainer;
use Tailors\Lib\Injector\InjectorContainerInterface;
use Tailors\Lib\Injector\ScopeContainer;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @covers \Tailors\Lib\Injector\InjectorContainer
 *
 * @internal
 */
final class InjectorContainerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInjectorContainerInterface(): void
    {
        $this->assertImplementsInterface(InjectorContainerInterface::class, InjectorContainer::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArguments(): void
    {
        $container = new InjectorContainer();
        $this->assertInstanceOf(ScopeContainer::class, $container->getGlobal());
        $this->assertSame([], $container->getNamespaces());
        $this->assertSame([], $container->getClasses());
    }

//    public function testConstructWithArguments(): void
//    {
//        $
//    }
}

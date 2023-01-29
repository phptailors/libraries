<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Tailors\Lib\Injector\Container;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @covers \Tailors\Lib\Injector\Container
 *
 * @internal
 */
final class ContainerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContainerInterface(): void
    {
        $this->assertImplementsInterface(ContainerInterface::class, Container::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGet(): void
    {
        $container = new Container();
        $this->assertNull($container->get('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHas(): void
    {
        $container = new Container();
        $this->assertFalse($container->has('foo'));
    }
}

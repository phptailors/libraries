<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\InstanceFactoryInterface;
use Tailors\Lib\Injector\InstanceFactoryWrapper;
use Tailors\Lib\Injector\ResolverInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\InstanceFactoryWrapper
 */
final class InstanceFactoryWrapperTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsInstanceFactoryInterface(): void
    {
        $this->assertImplementsInterface(InstanceFactoryInterface::class, InstanceFactoryWrapper::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstanceFactoryWrapper(): void
    {
        $resolver = $this->createStub(ResolverInterface::class);

        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): int => 123;
        $factory = new InstanceFactoryWrapper($callback, false);
        $this->assertSame($callback, $factory->callback());
        $this->assertFalse($factory->shared());
        $this->assertSame(123, $factory($resolver));
    }
}

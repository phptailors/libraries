<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractFactoryBase
 * @covers \Tailors\Lib\Injector\FactoryCallback
 *
 * @internal
 */
final class FactoryCallbackTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractFactoryBase(): void
    {
        $this->assertExtendsClass(AbstractFactoryBase::class, FactoryCallback::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsFactoryInterface(): void
    {
        $this->assertImplementsInterface(FactoryInterface::class, FactoryCallback::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCallback(): void
    {
        $resolver = $this->createStub(ResolverInterface::class);
        $callback = fn (ResolverInterface $resolver): ResolverInterface => $resolver;
        $factory = new FactoryCallback($callback);
        $this->assertSame($callback, $factory->getCallback());
        $this->assertSame($resolver, $factory->create($resolver));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testShared(): void
    {
        $callback = fn (ResolverInterface $resolver): ResolverInterface => $resolver;

        $factory = new FactoryCallback($callback);
        $this->assertFalse($factory->shared());

        $factory = new FactoryCallback($callback, false);
        $this->assertFalse($factory->shared());

        $factory = new FactoryCallback($callback, true);
        $this->assertTrue($factory->shared());
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\FactoryCallback
 *
 * @internal
 */
final class FactoryCallbackTest extends TestCase
{
    use ImplementsInterfaceTrait;

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
    public function testFactoryCallback(): void
    {
        $resolver = $this->createStub(ResolverInterface::class);
        $callback = fn (ResolverInterface $resolver): ResolverInterface => $resolver;
        $factory = new FactoryCallback($callback);
        $this->assertSame($callback, $factory->getCallback());
        $this->assertSame($resolver, $factory->create($resolver));
    }
}

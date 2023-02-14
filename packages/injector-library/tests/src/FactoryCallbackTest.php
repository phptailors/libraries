<?php

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\FactoryCallback;
use Tailors\Lib\Injector\FactoryInterface;
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
}

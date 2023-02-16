<?php

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\Registry
 */
final class RegistryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRegistryInterface(): void
    {
        $this->assertImplementsInterface(RegistryInterface::class, Registry::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsNamespacesWrapperInterface(): void
    {
        $this->assertImplementsInterface(NamespacesWrapperInterface::class, Registry::class);
    }
}

<?php

declare(strict_types=1);

namespace Tailors\Tests\Lib\Singleton;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

// SingletonInterface does not enforce singleton behaviour.
// This class is instantiable.
final class SingletonS29XX implements SingletonInterface
{
    use SingletonInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Tests\Lib\Singleton\SingletonInterfaceTrait
 *
 * @internal
 */
final class SingletonInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyClass(): SingletonS29XX
    {
        return new SingletonS29XX();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyClass();
        $this->assertImplementsInterface(SingletonInterface::class, $dummy);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetInstance(): void
    {
        $dummy = $this->createDummyClass();
        $dummy::$instance = 'instance';
        $this->assertSame($dummy::$instance, $dummy::getInstance());
    }
}

// vim: syntax=php sw=4 ts=4 et:

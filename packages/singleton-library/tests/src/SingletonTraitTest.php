<?php

declare(strict_types=1);

namespace Tailors\Tests\Lib\Singleton;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Singleton\SingletonTrait;
use Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;

class MinimalSingletonC91F82BJ
{
    use SingletonTrait;
}

class Singleton76YO7MV5
{
    use SingletonTrait;

    public mixed $value;

    private function initializeSingleton(): void
    {
        $this->value = 'initialized';
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Singleton\SingletonTrait
 *
 * @internal
 */
final class SingletonTraitTest extends TestCase
{
    use AssertIsSingletonTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMinimalSingleton(): void
    {
        $this->assertIsSingleton(MinimalSingletonC91F82BJ::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInitializeSingleton(): void
    {
        $obj = Singleton76YO7MV5::getInstance();
        $this->assertEquals('initialized', $obj->value);
    }
}

// vim: syntax=php sw=4 ts=4 et:

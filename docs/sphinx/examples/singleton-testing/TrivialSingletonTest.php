<?php

use Tailors\Lib\Singleton\SingletonTrait;
use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;

final class TrivialSingleton implements SingletonInterface
{
    use SingletonTrait;
}

final class TrivialSingletonTest extends \PHPUnit\Framework\TestCase
{
    use AssertIsSingletonTrait;

    public function testTrivialSingletonClassIsSingleton(): void
    {
        $this->assertIsSingleton(TrivialSingleton::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:

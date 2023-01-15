<?php

use Tailors\Testing\Lib\Singleton\AssertIsSingletonTrait;

trait PrivateConstructorTrait
{
    private function __construct()
    {
    }
}

trait PublicStaticGetInstanceTrait
{
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

trait PrivateCloneTrait
{
    private function __clone()
    {
    }
}

final class NoGetInstance
{
    use PrivateConstructorTrait;
}

final class PrivateGetInstance
{
    use PrivateConstructorTrait;
    private static function getInstance(): self
    {
        return new self();
    }
}

final class NonStaticGetInstance
{
    use PrivateConstructorTrait;
    public function getInstance(): self
    {
        return new self();
    }
}

final class NoPrivateConstructor
{
    use PublicStaticGetInstanceTrait;
}

final class NoPrivateClone
{
    use PrivateConstructorTrait;
    use PublicStaticGetInstanceTrait;
}

final class NoThrowingWakeup
{
    use PrivateConstructorTrait;
    use PublicStaticGetInstanceTrait;
    use PrivateCloneTrait;
}

final class ThrowingWrongExceptionWakeup
{
    use PrivateConstructorTrait;
    use PublicStaticGetInstanceTrait;
    use PrivateCloneTrait;

    public function __wakeup()
    {
        throw new \RuntimeException();
    }
}

final class NonSingletonTest extends \PHPUnit\Framework\TestCase
{
    use AssertIsSingletonTrait;

    public function testNoGetInstanceIsSingleton(): void
    {
        $this->assertIsSingleton(NoGetInstance::class);
    }

    public function testPrivateGetInstanceIsSingleton(): void
    {
        $this->assertIsSingleton(PrivateGetInstance::class);
    }

    public function testNonStaticGetInstanceIsSingleton(): void
    {
        $this->assertIsSingleton(NonStaticGetInstance::class);
    }

    public function testNoPrivateConstructorIsSingleton(): void
    {
        $this->assertIsSingleton(NoPrivateConstructor::class);
    }

    public function testNoPrivateCloneIsSingleton(): void
    {
        $this->assertIsSingleton(NoPrivateClone::class);
    }

    public function testNoThrowingWakeupIsSingleton(): void
    {
        $this->assertIsSingleton(NoThrowingWakeup::class);
    }

    public function testThrowingWrongExceptionWakeupIsSingleton(): void
    {
        $this->assertIsSingleton(ThrowingWrongExceptionWakeup::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:

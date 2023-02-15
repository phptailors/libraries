<?php declare(strict_types=1);

namespace Tailors\Lib\Singleton;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Singleton\SingletonException
 *
 * @internal
 */
final class SingletonExceptionTest extends TestCase
{
    use ExtendsClassTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsRuntimeException(): void
    {
        $this->assertExtendsClass(\RuntimeException::class, SingletonException::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSingletonException(): void
    {
        $exception = new SingletonException('Test message');
        $this->assertSame('Test message', $exception->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @covers \Tailors\Lib\Injector\CircularDependencyException
 */
final class CircularDependencyExceptionTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsException(): void
    {
        $this->assertExtendsClass(\Exception::class, CircularDependencyException::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsCircularDependencyExceptionInterface(): void
    {
        $this->assertImplementsInterface(
            CircularDependencyExceptionInterface::class,
            CircularDependencyException::class
        );
    }
}

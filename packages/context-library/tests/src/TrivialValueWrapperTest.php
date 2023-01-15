<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Context\TrivialValueWrapper;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Context\TrivialValueWrapper
 *
 * @internal
 */
final class TrivialValueWrapperTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, TrivialValueWrapper::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->getValue());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEnterContext(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->enterContext());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExitContext(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertFalse($cm->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et:

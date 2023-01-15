<?php
declare(strict_types=1);

namespace Tailors\Tests\Lib\Context;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Context\TrivialValueWrapper;

use function Tailors\Lib\Context\with;

use Tailors\Lib\Context\WithContextExecutor;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal
 *
 * @coversNothing
 */
final class functionsTest extends TestCase
{
    /**
     * @covers \Tailors\Lib\Context\with
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithWithoutArgs(): void
    {
        $executor = with();
        $this->assertInstanceOf(WithContextExecutor::class, $executor);
        $this->assertEquals([], $executor->getContext());
    }

    /**
     * @covers \Tailors\Lib\Context\with
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithWithArgs(): void
    {
        $executor = with('foo', 'bar');
        $this->assertInstanceOf(WithContextExecutor::class, $executor);

        $context = $executor->getContext();

        $this->assertEquals(2, count($context));
        $this->assertTrue(isset($context[0]));
        $this->assertTrue(isset($context[1]));
        $this->assertInstanceOf(TrivialValueWrapper::class, $context[0]);
        $this->assertInstanceOf(TrivialValueWrapper::class, $context[1]);
        $this->assertEquals('foo', $context[0]->getValue());
        $this->assertEquals('bar', $context[1]->getValue());
    }
}

// vim: syntax=php sw=4 ts=4 et:

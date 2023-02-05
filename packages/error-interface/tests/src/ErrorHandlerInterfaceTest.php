<?php declare(strict_types=1);

namespace Tailors\Tests\Lib\Error;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Error\ErrorHandlerInterface;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

final class ErrorHandler5VEU2 implements ErrorHandlerInterface
{
    use ErrorHandlerInterfaceTrait;
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Tests\Lib\Error\ErrorHandlerInterfaceTrait
 *
 * @internal This class is not covered by backward compatibility promise
 */
final class ErrorHandlerInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance(): ErrorHandler5VEU2
    {
        return new ErrorHandler5VEU2();
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ErrorHandlerInterface::class, $dummy);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvoke(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = false;
        $this->assertSame($dummy->invoke, $dummy(0, '', '', 0));
    }

    /**
     * @psalm-return array<array{0:array, 1:string}>
     */
    public static function provInvokeWithArgTypeError(): array
    {
        return [
            [[null, '', '', 0], 'int'],
            [[0, null, '', 0], 'string'],
            [[0, '', null, 0], 'string'],
            [[0, '', '', null], 'int'],
        ];
    }

    /**
     * @dataProvider provInvokeWithArgTypeError
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);

        /** @psalm-suppress MixedArgument */
        $dummy->__invoke(...$args);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInvokeWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->invoke = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('bool');
        $dummy(0, '', '', 0);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetErrorTypes(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorTypes = 0;
        $this->assertSame($dummy->errorTypes, $dummy->getErrorTypes());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetErrorTypesWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorTypes = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('int');
        $this->assertSame($dummy->errorTypes, $dummy->getErrorTypes());
    }
}

// vim: syntax=php sw=4 ts=4 et:

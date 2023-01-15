<?php
declare(strict_types=1);

namespace Tailors\Testing\Lib\Context;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait ExpectFunctionOnceWillReturnTrait
{
    abstract public function getContextFunctionMock(string $name): MockObject;

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\MethodNameNotConfiguredException
     * @throws \PHPUnit\Framework\MockObject\MethodParametersAlreadyConfiguredException
     * @throws \RuntimeException
     * @throws \PHPUnit\Framework\MockObject\IncompatibleReturnValueException
     */
    public function expectFunctionOnceWillReturn(string $function, array $args, mixed $return): void
    {
        $this->getContextFunctionMock($function)
            ->expects($this->once())
            ->with(...$args)
            ->willReturn($return)
        ;
    }
}

// vim: syntax=php sw=4 ts=4 et:

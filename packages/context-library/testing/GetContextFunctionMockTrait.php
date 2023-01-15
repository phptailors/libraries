<?php
declare(strict_types=1);

namespace Tailors\Testing\Lib\Context;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait GetContextFunctionMockTrait
{
    /**
     * @return MockObject
     */
    abstract public function getFunctionMock(string $namespace, string $function);

    public function getContextFunctionMock(string $name): MockObject
    {
        return $this->getFunctionMock('\Tailors\Lib\Context', $name);
    }
}

// vim: syntax=php sw=4 ts=4 et:

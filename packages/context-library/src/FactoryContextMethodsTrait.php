<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * Methods that any context-managed context factory should have.
 */
trait FactoryContextMethodsTrait
{
    /**
     * Pushes $this to ContextFactoryStack.
     */
    public function enterContext(): self
    {
        ContextFactoryStack::getInstance()->push($this);

        return $this;
    }

    /**
     * Pops from the top of ContextFactoryStack and returns ``false``.
     *
     * @return bool false
     */
    public function exitContext(\Throwable $exception = null): bool
    {
        ContextFactoryStack::getInstance()->pop();

        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:

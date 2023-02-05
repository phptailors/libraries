<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * Context management methods for error handler classes.
 */
trait ContextManagerTrait
{
    /**
     * Returns an integer used to mask the triggering of the error handler.
     *
     * When the handler is activated it usually calls ``set_error_handler($this, $this->getErrorTypes())``
     */
    abstract public function getErrorTypes(): int;

    /**
     * Sets this error handler object as error handler using PHP function
     * ``set_error_handler()`` and returns ``$this``.
     *
     * @return $this
     */
    public function enterContext(): self
    {
        set_error_handler($this, $this->getErrorTypes());

        return $this;
    }

    /**
     * Restores original error handler using PHP function
     * \restore_error_hander() and returns ``false``.
     *
     * @return bool always ``false``
     *
     * @psalm-return false
     */
    public function exitContext(\Throwable $exception = null): bool
    {
        restore_error_handler();

        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:

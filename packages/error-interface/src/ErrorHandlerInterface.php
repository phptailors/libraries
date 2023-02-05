<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * An interface for error handler objects.
 */
interface ErrorHandlerInterface
{
    /**
     * Actual error handler function.
     *
     * @param int    $severity level of the error raised
     * @param string $message  error message
     * @param string $file     file name the the error was raised in
     * @param int    $line     line number the error was raised at
     *
     * @return bool if it returns ``false``, then the normal error handler
     *              continues
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool;

    /**
     * Returns an integer used to mask the triggering of the error handler.
     *
     * When the handler is activated it usually calls ``set_error_handler($this, $this->getErrorTypes())``
     */
    public function getErrorTypes(): int;
}

// vim: syntax=php sw=4 ts=4 et:

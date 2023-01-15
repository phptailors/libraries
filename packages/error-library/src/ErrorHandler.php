<?php

declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * Context-managed error handler that calls user-provided function.
 *
 * @psalm-type ErrorHandlerFunction callable(int,string,string,int):bool
 */
class ErrorHandler extends AbstractManagedErrorHandler
{
    /**
     * @var callable
     *
     * @psalm-var ErrorHandlerFunction
     */
    protected mixed $errorHandler;

    /**
     * Initializes the object.
     *
     * @param callable $errorHandler user-provided error handler function
     * @param int      $errorTypes   can be used to mask the triggering of the error
     *                               handler function
     *
     * @psalm-param ErrorHandlerFunction $errorHandler
     */
    public function __construct(callable $errorHandler, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->errorHandler = $errorHandler;
        parent::__construct($errorTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return call_user_func($this->getErrorHandler(), $severity, $message, $file, $line);
    }

    /**
     * Returns the $errorHandler provided to constructor.
     *
     * @psalm-return ErrorHandlerFunction
     */
    public function getErrorHandler(): callable
    {
        return $this->errorHandler;
    }
}

// vim: syntax=php sw=4 ts=4 et:

<?php

declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * Context-managed error handler that calls user-provided function with predefined $file and $line arguments.
 */
class CallerErrorHandler extends ErrorHandler
{
    use CallerErrorHandlerTrait;

    /**
     * Initializes the object.
     *
     * @psalm-param callable(int,string,string,int):bool $errorHandler
     */
    public function __construct(callable $errorHandler, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->initCallerErrorHandler(1 + $distance);
        parent::__construct($errorHandler, $errorTypes);
    }
}

// vim: syntax=php sw=4 ts=4 et:

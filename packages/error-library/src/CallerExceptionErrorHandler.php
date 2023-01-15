<?php

declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * Context-managed error handler that throws an exception with predefined $file and $line arguments.
 *
 * @psalm-import-type ExceptionGenerator from ExceptionErrorHandler
 */
final class CallerExceptionErrorHandler extends ExceptionErrorHandler
{
    use CallerErrorHandlerTrait;

    /**
     * Initializes the object.
     *
     * @psalm-param ExceptionGenerator $exceptionGenerator
     */
    public function __construct(callable $exceptionGenerator, int $distance = 1, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->initCallerErrorHandler(1 + $distance);
        parent::__construct($exceptionGenerator, $errorTypes);
    }
}

// vim: syntax=php sw=4 ts=4 et:

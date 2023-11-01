<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * A shortcut to EmptyErrorHandler::getInstance().
 */
function empty_error_handler(): EmptyErrorHandler
{
    return EmptyErrorHandler::getInstance();
}

/**
 * A shortcut to new ErrorHandler(...).
 *
 * @param callable $errorHandler user-provided error handler function
 * @param int      $errorTypes   can be used to mask the triggering of the error
 *                               handler function
 *
 * @psalm-param callable(int,string,string,int):bool $errorHandler
 */
function error_handler(callable $errorHandler, int $errorTypes = E_ALL | E_STRICT): ErrorHandler
{
    return new ErrorHandler($errorHandler, $errorTypes);
}

/**
 * Creates and returns new ExceptionErrorHandler.
 *
 * If ``$arg`` is a callable it should have the prototype
 *
 * ```php
 * function f(int $severity, string $message, string $file, int $line)
 * ```
 *
 * and it should return new exception object.
 *
 * If it is a class name, the class should provide constructor
 * having interface compatible with PHP's \ErrorException class.
 *
 * @param callable|string $arg        either a callable or an exception's class name
 * @param int             $errorTypes error types handled by the new handler
 *
 * @throws \InvalidArgumentException
 *
 * @psalm-param string|callable(int,string,string,int):\Exception $arg
 */
function exception_error_handler(callable|string $arg = null, int $errorTypes = E_ALL | E_STRICT): ExceptionErrorHandler
{
    $exceptionGenerator = ExceptionErrorHandler::makeExceptionGenerator($arg);

    return new ExceptionErrorHandler($exceptionGenerator, $errorTypes);
}

/**
 * A shortcut to new CallerErrorHandler(...).
 *
 * @param callable   $errorHandler user-provided error handler function
 * @param int<0,max> $distance     the distance from our caller to his caller
 * @param int        $errorTypes   error types handled by the new handler
 *
 * @psalm-param callable(int,string,string,int):bool $errorHandler
 */
function caller_error_handler(
    callable $errorHandler,
    int $distance = 1,
    int $errorTypes = E_ALL | E_STRICT
): CallerErrorHandler {
    return new CallerErrorHandler($errorHandler, 1 + $distance, $errorTypes);
}

/**
 * Creates and returns new CallerExceptionErrorHandler.
 *
 * If ``$arg`` is a callable it should have the prototype
 *
 * ```php
 * function f(int $severity, string $message, string $file, int $line)
 * ```
 *
 * and it should return new exception object.
 *
 * If it is a class name, the class should provide constructor
 * having interface compatible with PHP's \ErrorException class.
 *
 * @param callable|string $arg        either a callable or an exception's class name
 * @param int<0,max>      $distance   the distance from our caller to his caller
 * @param int             $errorTypes error types handled by the new handler
 *
 * @throws \InvalidArgumentException
 *
 * @psalm-param string|callable(int,string,string,int):\Exception $arg
 */
function caller_exception_error_handler(
    callable|string $arg = null,
    int $distance = 1,
    int $errorTypes = E_ALL | E_STRICT
): CallerExceptionErrorHandler {
    $exceptionGenerator = ExceptionErrorHandler::makeExceptionGenerator($arg);

    return new CallerExceptionErrorHandler($exceptionGenerator, 1 + $distance, $errorTypes);
}

// vim: syntax=php sw=4 ts=4 et:

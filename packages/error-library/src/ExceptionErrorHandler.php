<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * An error handler which raises custom exception.
 *
 * @psalm-type ExceptionGenerator (callable(int,string,string,int):\Exception)
 */
class ExceptionErrorHandler extends AbstractManagedErrorHandler
{
    /**
     * @var callable
     *
     * @psalm-var ExceptionGenerator
     *
     * @psalm-readonly
     */
    protected readonly mixed $exceptionGenerator;

    /**
     * Initializes the object.
     *
     * @param int $errorTypes error types to be handled by this handler
     *
     * @psalm-param ExceptionGenerator $exceptionGenerator
     */
    public function __construct(callable $exceptionGenerator, int $errorTypes = E_ALL | E_STRICT)
    {
        $this->exceptionGenerator = $exceptionGenerator;
        parent::__construct($errorTypes);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        throw $this->getException($severity, $message, $file, $line);
    }

    /**
     * Converts argument $arg to an exception generator.
     *
     * An exception generator is a function (or callable object) which creates
     * and returns exception objects.
     *
     * @param callable|string $arg
     *                             Either a callable or a class name. If it's a callable
     *                             then it gets returned as is. If it is a class name,
     *                             then a new callable is returned which creates and
     *                             returns a new instance of this class. The constructor
     *                             of the class must have interface compatible with the
     *                             constructor of PHP's ``\ErrorException`` class. The
     *                             class must extend ``\Exception``.If $arg is null, then
     *                             ``\ErrorException`` is used as a class.
     *
     * @throws \InvalidArgumentException
     *
     * @psalm-param ExceptionGenerator|string $arg
     *
     * @psalm-return ExceptionGenerator
     */
    public static function makeExceptionGenerator(callable|string $arg = null): callable
    {
        if (is_callable($arg)) {
            return $arg;
        }

        if (is_null($arg)) {
            $class = \ErrorException::class;
        } else {
            $class = $arg;
        }

        if (class_exists($class) && is_a($class, \Exception::class, true)) {
            return function (int $severity, string $message, string $file, int $line) use ($class): object {
                /** @psalm-suppress UnsafeInstantiation */ // don't know how to assert that $class::__construct is ok
                return new $class($message, 0, $severity, $file, $line);
            };
        }

        throw new \InvalidArgumentException(
            'argument 1 to '.__METHOD__.'() must be a callable, a class'.
            ' name or null, '.gettype($arg).' given'
        );
    }

    /**
     * Returns the $exceptionGenerator provided to constructor.
     *
     * @psalm-return ExceptionGenerator
     */
    public function getExceptionGenerator(): callable
    {
        return $this->exceptionGenerator;
    }

    /**
     * Creates and returns new exception using the encapsulated $exceptionGenerator.
     *
     * @param int    $severity The level of error raised
     * @param string $message  The error message, as a string
     * @param string $file     The file name that the error was raised in
     * @param int    $line     The line number the error was raised at
     */
    public function getException(int $severity, string $message, string $file, int $line): \Exception
    {
        $generator = $this->getExceptionGenerator();

        return call_user_func($generator, $severity, $message, $file, $line);
    }
}

// vim: syntax=php sw=4 ts=4 et:

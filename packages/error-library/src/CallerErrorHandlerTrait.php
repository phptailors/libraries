<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * Provides methods for CallerXxxHandler classes.
 */
trait CallerErrorHandlerTrait
{
    /**
     * @var string
     */
    protected $callerFile;

    /**
     * @var int
     */
    protected $callerLine;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return parent::__invoke($severity, $message, $this->getCallerFile(), $this->getCallerLine());
    }

    /**
     * Returns the caller's file name as determined by the constructor.
     */
    public function getCallerFile(): string
    {
        return $this->callerFile;
    }

    /**
     * Returns the caller's line number as determined by the constructor.
     */
    public function getCallerLine(): int
    {
        return $this->callerLine;
    }

    /**
     * @psalm-param int<0,max> $distance
     */
    protected function initCallerErrorHandler(int $distance): void
    {
        /** @psalm-var non-empty-array<array{file: string, line: int}> */
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1 + $distance);
        $caller = end($trace);

        $this->callerFile = $caller['file'];
        $this->callerLine = $caller['line'];
    }
}

// vim: syntax=php sw=4 ts=4 et:

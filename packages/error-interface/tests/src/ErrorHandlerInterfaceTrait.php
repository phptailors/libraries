<?php declare(strict_types=1);

namespace Tailors\Lib\Error;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait ErrorHandlerInterfaceTrait
{
    public mixed $invoke;
    public mixed $errorTypes;

    /** @psalm-suppress MixedInferredReturnType */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return $this->invoke;
    }

    /** @psalm-suppress MixedInferredReturnType */
    public function getErrorTypes(): int
    {
        return $this->errorTypes;
    }
}

// vim: syntax=php sw=4 ts=4 et:

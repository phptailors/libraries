<?php

declare(strict_types=1);

namespace Tailors\Lib\Error;

use Tailors\Lib\Context\ContextManagerInterface;
use Tailors\Lib\Singleton\SingletonTrait;

/**
 * Context-managed error handler disabler.
 */
final class EmptyErrorHandler implements ErrorHandlerInterface, ContextManagerInterface
{
    use ContextManagerTrait;
    use SingletonTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(int $severity, string $message, string $file, int $line): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorTypes(): int
    {
        return E_ALL | E_STRICT;
    }
}

// vim: syntax=php sw=4 ts=4 et:

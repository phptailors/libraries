<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\Lib\Singleton\SingletonTrait;

/**
 * Default context factory.
 */
final class DefaultContextFactory implements ContextFactoryInterface, SingletonInterface
{
    use SingletonTrait;

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg): ContextManagerInterface
    {
        if (is_a($arg, ContextManagerInterface::class)) {
            return $arg;
        }
        if (is_resource($arg)) {
            return new ResourceContextManager($arg);
        }

        return new TrivialValueWrapper($arg);
    }
}

// vim: syntax=php sw=4 ts=4 et:

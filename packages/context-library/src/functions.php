<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * Creates an executor object which invokes user function within a context.
 */
function with(mixed ...$args): ExecutorInterface
{
    $context = array_map(function (mixed $arg): ContextManagerInterface {
        return ContextFactoryStack::getInstance()->getContextManager($arg) ??
               DefaultContextFactory::getInstance()->getContextManager($arg);
    }, $args);

    return new WithContextExecutor($context);
}

// vim: syntax=php sw=4 ts=4 et:

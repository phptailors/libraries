<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * Abstract base class for managed custom context factories.
 *
 * A managed context factory implements enterContext() and exitContext(), so it
 * works as a context manager. A class that extends AbstractManagedContextFactory
 * must still implement the getContextManager() method.
 */
abstract class AbstractManagedContextFactory implements ContextFactoryInterface, ContextManagerInterface
{
    use FactoryContextMethodsTrait;
}

// vim: syntax=php sw=4 ts=4 et:

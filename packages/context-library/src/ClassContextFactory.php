<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * A factory that associates classes with context managers.
 *
 * @psalm-type ContextManagerWrapper = callable(mixed):ContextManagerInterface
 * @psalm-type ContextManagerRegistry = array<string, ContextManagerWrapper>
 */
final class ClassContextFactory extends AbstractManagedContextFactory
{
    /**
     * @psalm-var ContextManagerRegistry
     */
    protected array $registry;

    /**
     * Initializes the object.
     *
     * @psalm-param ContextManagerRegistry $wrappers
     */
    public function __construct(array $wrappers = [])
    {
        $this->initialize($wrappers);
    }

    /**
     * Returns the internal registry which maps class names into their
     * corresponding context manager generators.
     *
     * @psalm-return ContextManagerRegistry
     */
    public function getRegistry(): array
    {
        return $this->registry;
    }

    /**
     * Register new class.
     *
     * @template T of ContextManagerInterface
     *
     * @psalm-param class-string<T>|callable(mixed):T $contextManager
     *
     * @return ClassContextFactory $this
     *
     * @throws \InvalidArgumentException
     */
    public function register(string $class, string|callable $contextManager): ClassContextFactory
    {
        if (is_callable($contextManager)) {
            $wrapper = $contextManager;
        } elseif (class_exists($contextManager)) {
            $wrapper = function (mixed $arg) use ($contextManager): ContextManagerInterface {
                return new $contextManager($arg);
            };
        } else {
            throw new \InvalidArgumentException(
                'argument 2 to '.__METHOD__.'() must be a callable or a'.
                ' class name, '.gettype($contextManager).' given'
            );
        }
        $this->registry[ltrim($class, '\\')] = $wrapper;

        return $this;
    }

    /**
     * Unregister the $class.
     *
     * @return ClassContextFactory $this
     */
    public function remove(string $class): ClassContextFactory
    {
        unset($this->registry[ltrim($class, '\\')]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg): ?ContextManagerInterface
    {
        if (is_object($arg)) {
            $class = get_class($arg);
            if (null !== ($wrapper = $this->registry[$class] ?? null)) {
                return call_user_func($wrapper, $arg);
            }
        }

        return null;
    }

    /**
     * Initializes the object with $wrappers.
     *
     * @psalm-param ContextManagerRegistry $wrappers
     */
    protected function initialize(array $wrappers): void
    {
        $this->registry = [];

        foreach ($wrappers as $key => $val) {
            $this->register($key, $val);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:

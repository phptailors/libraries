<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

final class Injector implements InjectorInterface
{
    private ContextAwareResolverInterface $resolver;

    public function __construct(ContextAwareResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Invoke *$callback*, with a resolver configured to work in context of *$class*.
     *
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function new(string $class, \Closure $callback): object
    {
    }

    /**
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function method(string $class, string $name, \Closure $callback): mixed
    {
    }

    /**
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function function(string $name, \Closure $callback): mixed
    {
    }
}

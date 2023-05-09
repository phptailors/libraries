<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait ContainerInterfaceTrait
{
    public mixed $get;

    public mixed $has;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get(string $id): mixed
    {
        return $this->get;
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     */
    public function has(string $id): bool
    {
        return $this->has;
    }

    /**
     * Create *$alias* for *$target*.
     */
    public function alias(string $alias, string $target): void
    {
    }

    /**
     * Store the *$instance* under key *$id*.
     *
     * Calling *instance($id, $instance)* makes *get($id)$ to return the *$instance*.
     */
    public function instance(string $id, mixed $instance): void
    {
    }

    /**
     * Bind *$callback* to *$id* to be used as a factory.
     *
     * Calling *bind($id, $callback)* makes *get($id)* to return the result of
     * calling *$callback*.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function bind(string $id, \Closure $callback): void
    {
    }

    /**
     * Lazily create an instance using *$callback*.
     *
     * This makes *get($id)* to invoke *$callback* once, when invoked for the
     * first time with the given *$id*. The result of *$callback* is returned
     * on each invocation of *get($id)* for the given *$id*.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function singleton(string $id, \Closure $callback): void
    {
    }
}

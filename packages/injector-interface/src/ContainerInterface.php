<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id identifier of the entry to look for
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get(string $id): mixed;

    /**
     * Create *$alias* for *$target*.
     */
    public function alias(string $alias, string $target): void;

    /**
     * Store the *$instance* under key *$id*.
     *
     * Calling *instance($id, $instance)* makes *get($id)$ to return the *$instance*.
     */
    public function instance(string $id, mixed $instance): void;

    /**
     * Store *$callback* under *$id* to be used as a factory.
     *
     * Calling *factory($id, $callback)* makes *get($id)* to return the result
     * of calling *$callback*. The *$callback* is called every time *$id$ is
     * requested.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function factory(string $id, \Closure $callback): void;

    /**
     * Lazily create an instance using *$callback*.
     *
     * This makes *get($id)* to invoke *$callback* once, when invoked for the
     * first time with the given *$id*. The result of *$callback* is returned
     * on each invocation of *get($id)* for the given *$id*.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function singleton(string $id, \Closure $callback): void;
}

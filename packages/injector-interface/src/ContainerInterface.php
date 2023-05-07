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

    public function hasItem(string $id): bool;

    /**
     * @throws NotFoundExceptionInterface
     */
    public function getItem(string $id): ItemInterface;

    /**
     * Remove item stored under key $id from the container.
     */
    public function unsetItem(string $id): void;

    /**
     * Create $alias for $target.
     */
    public function alias(string $alias, string $target): void;

    /**
     * Store the $instance under key $id.
     */
    public function instance(string $id, object $instance): void;

    /**
     * Bind a callback that returns values and store the callback under $id.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function bind(string $id, \Closure $callback): void;

    /**
     * Lazily create single instance and store under key $id.
     *
     * @psalm-param \Closure(ResolverInterface):object $callback
     */
    public function singleton(string $id, \Closure $callback): void;
}

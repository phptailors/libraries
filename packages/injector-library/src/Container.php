<?php
declare(strict_types=1);

namespace Tailors\Lib\Injector;

use Psr\Container\ContainerInterface;

/**
 * Dependency Injection Container.
 *
 * @api
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class Container implements ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id identifier of the entry to look for
     *
     * @return mixed entry
     *
     * @throws NotFoundException  no entry was found for **this** identifier
     * @throws ContainerException error while retrieving the entry
     */
    public function get(string $id): mixed
    {
        return null;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundException`.
     *
     * @param string $id identifier of the entry to look for
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return false;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ItemContainerInterface
{
    public function hasItem(string $id): bool;

    /**
     * @throws NotFoundExceptionInterface
     */
    public function getItem(string $id): ItemInterface;

    /**
     * Remove item stored under key $id from the container.
     */
    public function unsetItem(string $id): void;
}

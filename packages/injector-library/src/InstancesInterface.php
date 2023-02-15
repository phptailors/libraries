<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface InstancesInterface
{
    /**
     * @psalm-return array<class-string,object>
     */
    public function getInstances(): array;

    /**
     * Returns true if instance of given *$type* exists.
     */
    public function instanceExists(string $type): bool;

    /**
     * Registers singleton of a given *$type*.
     */
    public function instanceSet(string $type, object $instance): void;

    /**
     * Unregister instance registered under *$type*.
     */
    public function instanceUnset(string $type): void;

    /**
     * Returns direct target assigned to instance.
     *
     * @throws NotFoundExceptionInterface if *$type* does not exist
     */
    public function instanceGet(string $type): string;
}

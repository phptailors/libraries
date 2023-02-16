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
     * Returns an array of type => instance assignments.
     *
     * @psalm-return array<object>
     */
    public function instancesArray(): array;

    /**
     * Returns true if *$type* has assigned instance.
     *
     * @psalm-param array-key $type
     */
    public function instanceExists(mixed $type): bool;

    /**
     * Assigns *$instance* to *$type*.
     */
    public function instanceSet(string $type, object $instance): void;

    /**
     * Unassign instance from *$type*.
     *
     * @psalm-param array-key $type
     */
    public function instanceUnset(mixed $type): void;

    /**
     * Returns instance assigned to *$type*.
     *
     * @throws NotFoundExceptionInterface if *$type* does not exist
     *
     * @psalm-param array-key $type
     */
    public function instanceGet(mixed $type): object;
}

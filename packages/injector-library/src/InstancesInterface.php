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
     * @psalm-return array<object>
     */
    public function instancesArray(): array;

    /**
     * @psalm-param array-key $type
     */
    public function instanceExists(mixed $type): bool;

    public function instanceSet(string $type, object $instance): void;

    /**
     * @psalm-param array-key $type
     */
    public function instanceUnset(mixed $type): void;

    /**
     * @throws NotFoundExceptionInterface if *$type* does not exist
     *
     * @psalm-param array-key $type
     */
    public function instanceGet(mixed $type): object;
}

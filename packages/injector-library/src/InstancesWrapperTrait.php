<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait InstancesWrapperTrait
{
    abstract public function getInstances(): InstancesInterface;

    /**
     * Returns an array of type => instance assignments.
     *
     * @psalm-return array<object>
     */
    public function instancesArray(): array
    {
        return $this->getInstances()->instancesArray();
    }

    /**
     * Returns true if *$type* has assigned instance.
     *
     * @psalm-param array-key $type
     */
    public function instanceExists(mixed $type): bool
    {
        return $this->getInstances()->instanceExists($type);
    }

    /**
     * Assigns *$instance* to *$type*.
     */
    public function instanceSet(string $type, object $instance): void
    {
        $this->getInstances()->instanceSet($type, $instance);
    }

    /**
     * Remove *$instance*.
     *
     * @psalm-param array-key $type
     */
    public function instanceUnset(mixed $type): void
    {
        $this->getInstances()->instanceUnset($type);
    }

    /**
     * Returns instance assigned to *$type*.
     *
     * @throws NotFoundExceptionInterface if *$type* does not exist
     *
     * @psalm-param array-key $type
     */
    public function instanceGet(mixed $type): object
    {
        return $this->getInstances()->instanceGet($type);
    }
}

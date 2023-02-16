<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Instances implements InstancesInterface
{
    /**
     * @psalm-var NSArray<object>
     */
    private NSArray $instances;

    /**
     * @psalm-param array<object> $instances
     */
    public function __construct(array $instances = [])
    {
        $this->instances = new NSArray($instances);
    }

    /**
     * @psalm-return array<object>
     */
    public function instancesArray(): array
    {
        return $this->instances->getArrayCopy();
    }

    /**
     * @psalm-param array-key $type
     */
    public function instanceExists(mixed $type): bool
    {
        return $this->instances->offsetIsSet($type);
    }

    public function instanceSet(string $type, object $instance): void
    {
        $this->instances[$type] = $instance;
    }

    /**
     * @psalm-param array-key $type
     */
    public function instanceUnset(mixed $type): void
    {
        unset($this->instances[$type]);
    }

    /**
     * @throws NotFoundException
     *
     * @psalm-param array-key $type
     */
    public function instanceGet(mixed $type): object
    {
        if (!$this->instances->offsetIsSet($type)) {
            throw new NotFoundException(sprintf('instance %s not found', var_export($type, true)));
        }

        return $this->instances[$type];
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Factories implements FactoriesInterface
{
    /**
     * @psalm-var NSArray<FactoryInterface>
     */
    private NSArray $factories;

    /**
     * @psalm-param array<FactoryInterface> $factories
     */
    public function __construct(array $factories = [])
    {
        $this->factories = new NSArray($factories);
    }

    /**
     * @psalm-return array<FactoryInterface>
     */
    public function factoriesArray(): array
    {
        return $this->factories->getArrayCopy();
    }

    /**
     * @psalm-param array-key $type
     */
    public function factoryExists(mixed $type): bool
    {
        return $this->factories->offsetIsSet($type);
    }

    public function factorySet(string $type, FactoryInterface $factory): void
    {
        $this->factories[$type] = $factory;
    }

    /**
     * @psalm-param array-key $type
     */
    public function factoryUnset(mixed $type): void
    {
        unset($this->factories[$type]);
    }

    /**
     * @throws NotFoundException
     */
    public function factoryGet(string $type): FactoryInterface
    {
        if (!$this->factories->offsetIsSet($type)) {
            throw new NotFoundException(sprintf('factory %s not found', var_export($type, true)));
        }

        return $this->factories[$type];
    }
}

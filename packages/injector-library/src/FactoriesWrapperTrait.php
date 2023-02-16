<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait FactoriesWrapperTrait
{
    abstract public function getFactories(): FactoriesInterface;

    /**
     * @psalm-return array<FactoryInterface>
     */
    public function factoriesArray(): array
    {
        return $this->getFactories()->factoriesArray();
    }

    /**
     * @psalm-param array-key $type
     */
    public function factoryExists(mixed $type): bool
    {
        return $this->getFactories()->factoryExists($type);
    }

    public function factorySet(string $type, FactoryInterface $factory): void
    {
        $this->getFactories()->factorySet($type, $factory);
    }

    /**
     * @psalm-param array-key $type
     */
    public function factoryUnset(mixed $type): void
    {
        $this->getFactories()->factoryUnset($type);
    }

    /**
     * @throws NotFoundExceptionInterface if *$type* does not exist
     */
    public function factoryGet(string $type): FactoryInterface
    {
        return $this->getFactories()->factoryGet($type);
    }
}

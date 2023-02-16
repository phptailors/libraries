<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface FactoriesInterface
{
    /**
     * @psalm-return array<FactoryInterface>
     */
    public function factoriesArray(): array;

    /**
     * @psalm-param array-key $type
     */
    public function factoryExists(mixed $type): bool;

    public function factorySet(string $type, FactoryInterface $factory): void;

    /**
     * @psalm-param array-key $type
     */
    public function factoryUnset(mixed $type): void;

    /**
     * @throws NotFoundExceptionInterface if *$type* does not exist
     */
    public function factoryGet(string $type): FactoryInterface;
}

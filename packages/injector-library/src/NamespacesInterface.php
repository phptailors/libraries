<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface NamespacesInterface
{
    /**
     * @psalm-return array<ScopeInterface>
     */
    public function nsArray(): array;

    /**
     * @psalm-param array-key $name
     */
    public function nsExists(mixed $name): bool;

    public function nsSet(string $name, ScopeInterface $scope): void;

    /**
     * @psalm-param array-key $name
     */
    public function nsUnset(mixed $name): void;

    /**
     * @throws NotFoundExceptionInterface
     */
    public function nsGet(string $name): ScopeInterface;
}

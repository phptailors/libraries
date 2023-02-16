<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait NamespacesWrapperTrait
{
    abstract public function getNamespaces(): NamespacesInterface;

    /**
     * @psalm-return array<ScopeInterface>
     */
    public function nsArray(): array
    {
        return $this->getNamespaces()->nsArray();
    }

    /**
     * @psalm-param array-key $name
     */
    public function nsExists(mixed $name): bool
    {
        return $this->getNamespaces()->nsExists($name);
    }

    public function nsSet(string $name, ScopeInterface $scope): void
    {
        $this->getNamespaces()->nsSet($name, $scope);
    }

    /**
     * @psalm-param array-key $name
     */
    public function nsUnset(mixed $name): void
    {
        $this->getNamespaces()->nsUnset($name);
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function nsGet(string $name): ScopeInterface
    {
        return $this->getNamespaces()->nsGet($name);
    }
}

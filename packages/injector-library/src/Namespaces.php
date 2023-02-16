<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Namespaces implements NamespacesInterface
{
    /**
     * @psalm-var NSArray<ScopeInterface>
     */
    private NSArray $namespaces;

    /**
     * @psalm-param array<ScopeInterface> $namespaces
     */
    public function __construct(array $namespaces = [])
    {
        $this->namespaces = new NSArray($namespaces);
    }

    /**
     * @psalm-return array<ScopeInterface>
     */
    public function nsArray(): array
    {
        return $this->namespaces->getArrayCopy();
    }

    /**
     * @psalm-param array-key $name
     */
    public function nsExists(mixed $name): bool
    {
        return $this->namespaces->offsetIsSet($name);
    }

    public function nsSet(string $name, ScopeInterface $scope): void
    {
        $this->namespaces[$name] = $scope;
    }

    /**
     * @psalm-param array-key $name
     */
    public function nsUnset(mixed $name): void
    {
        unset($this->namespaces[$name]);
    }

    /**
     * @throws NotFoundException
     */
    public function nsGet(string $name): ScopeInterface
    {
        if (!$this->namespaces->offsetIsSet($name)) {
            throw new NotFoundException(sprintf('namespace %s not found', var_export($name, true)));
        }

        return $this->namespaces[$name];
    }
}

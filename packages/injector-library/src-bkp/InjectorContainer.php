<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class InjectorContainer implements InjectorContainerInterface
{
    private ScopeContainerInterface $global;

    /**
     * array<string,ScopeContainerInterface>.
     */
    private array $classes;

    /**
     * array<string,ScopeContainerInterface>.
     */
    private array $namespaces;

    /**
     * @psalm-param array<string,ScopeContainerInterface> $classes
     * @psalm-param array<string,ScopeContainerInterface> $namespaces
     */
    public function __construct(ScopeContainerInterface $global = null, array $classes = null, array $namespaces = null)
    {
        $this->global = $global ?? new ScopeContainer();
        $this->classes = self::arrayNormalizeKeys($classes ?? []);
        $this->namespaces = self::arrayNormalizeKeys($namespaces ?? []);
    }

    public function getClasses(): array
    {
        return $this->classes;
    }

    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    public function hasClass(string $class): bool
    {
        $key = self::normalizeKey($class);

        return isset($this->classes[$key]);
    }

    public function hasNamespace(string $namespace): bool
    {
        $key = self::normalizeKey($namespace);

        return isset($this->namespaces[$key]);
    }

    public function getGlobal(): ScopeContainerInterface
    {
        return $this->global;
    }

    /**
     * @throws NotFoundException
     */
    public function getClass(string $class): ScopeContainerInterface
    {
        $key = self::normalizeKey($class);

        return isset($this->classes[$key]);
    }

    /**
     * @throws NotFoundException
     */
    public function getNamespace(string $namespace): ScopeContainerInterface
    {
        $key = self::normalizeKey($namespace);

        return isset($this->namespaces[$key]);
    }

    public function setClass(string $class, ScopeContainerInterface $scope): void
    {
        $key = self::normalizeKey($class);

        $this->classes[$key] = $scope;
    }

    public function setNamespace(string $namespace, ScopeContainerInterface $scope): void
    {
        $key = self::normalizeKey($namespace);

        $this->namespaces[$key] = $scope;
    }

    public function unsetClass(string $class): void
    {
        $key = self::normalizeKey($class);

        unset($this->classes[$key]);
    }

    public function unsetNamespace(string $namespace): void
    {
        $key = self::normalizeKey($namespace);

        unset($this->namespaces[$key]);
    }

    private static function normalizeKey(string $name): string
    {
        return strtolower(ltrim($name, '\\'));
    }

    /**
     * @psalm-param array<string, mixed> $array
     */
    private static function arrayNormalizeKeys(array $array): array
    {
        return array_combine(array_map([self::class, 'normalizeKey'], array_keys($array)), array_values($array));
    }
}

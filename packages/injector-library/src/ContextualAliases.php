<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TScopePath from ContextualArray
 * @psalm-import-type TItemPath from ContextualArray
 *
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 */
final class ContextualAliases implements ContextualAliasesInterface
{
    /**
     * @psalm-param TAliases $aliases
     */
    public function __construct(private array $aliases)
    {
    }

    /**
     * @psalm-param TScopePath $scope
     */
    public function get(string $alias, array $scope): ?string
    {
        /** @psalm-var ?string */
        return ContextualArray::get($this->aliases, [...$scope, $alias]);
    }

    /**
     * @psalm-param TScopePath $scope
     */
    public function set(string $alias, array $scope, string $id): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        ContextualArray::set($this->aliases, [...$scope, $alias], $id);
    }

    /**
     * @psalm-param TScopePath $scope
     */
    public function del(string $alias, array $scope): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        ContextualArray::del($this->aliases, [...$scope, $alias]);
    }

    /**
     * @psalm-param array<array-key|array> $lookup
     *
     * @psalm-return ?TItemPath
     */
    public function lookup(string $key, array $lookup, string &$id = null): ?array
    {
        return ContextualArray::lookup($this->aliases, [$lookup, $key], $id);
    }
}

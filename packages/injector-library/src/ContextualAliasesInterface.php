<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TScopeType from ContextualArray
 * @psalm-import-type TScopePath from ContextualArray
 * @psalm-import-type TItemPath from ContextualArray
 */
interface ContextualAliasesInterface
{
    /**
     * @psalm-param TScopePath $scope
     */
    public function get(string $alias, array $scope): ?string;

    /**
     * @psalm-param TScopePath $scope
     */
    public function set(string $alias, array $scope, string $id): void;

    /**
     * @psalm-param TScopePath $scope
     */
    public function unset(string $alias, array $scope): void;

    /**
     * @psalm-param array<array-key|array> $lookup
     *
     * @psalm-return ?TItemPath
     */
    public function lookup(string $key, array $lookup, string &$id = null): ?array;
}

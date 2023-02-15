<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface AliasesInterface
{
    /**
     * @psalm-return array<string,string>
     */
    public function getAliases(): array;

    /**
     * Returns true if *$alias* exists.
     */
    public function aliasExists(string $alias): bool;

    /**
     * @throws CyclicAliasExceptionInterface
     */
    public function aliasSet(string $alias, string $target): void;

    /**
     * Remove *$alias*.
     */
    public function aliasUnset(string $alias): void;

    /**
     * Returns direct target assigned to alias.
     *
     * @throws NotFoundExceptionInterface if $alias does not exist
     */
    public function aliasGet(string $alias): string;

    /**
     * Resolves alias recursively.
     */
    public function aliasResolve(string $alias): string;
}

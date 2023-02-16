<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait AliasesWrapperTrait
{
    abstract public function getAliases(): AliasesInterface;

    /**
     * @psalm-return array<string,string>
     */
    public function aliasesArray(): array
    {
        return $this->getAliases()->aliasesArray();
    }

    /**
     * Returns true if *$alias* exists.
     */
    public function aliasExists(string $alias): bool
    {
        return $this->getAliases()->aliasExists($alias);
    }

    /**
     * @throws CyclicAliasExceptionInterface
     */
    public function aliasSet(string $alias, string $target): void
    {
        $this->getAliases()->aliasSet($alias, $target);
    }

    /**
     * Remove *$alias*.
     */
    public function aliasUnset(string $alias): void
    {
        $this->getAliases()->aliasUnset($alias);
    }

    /**
     * Returns direct target assigned to alias.
     *
     * @throws NotFoundExceptionInterface
     */
    public function aliasGet(string $alias): string
    {
        return $this->getAliases()->aliasGet($alias);
    }

    /**
     * Resolves alias recursively.
     *
     * @throws NotFoundExceptionInterface
     */
    public function aliasResolve(string $alias): string
    {
        return $this->getAliases()->aliasResolve($alias);
    }
}

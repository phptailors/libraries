<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Aliases implements AliasesInterface
{
    /**
     * @psalm-var array<string,string>
     */
    private array $aliases;

    /**
     * @throws CyclicAliasException
     *
     * @psalm-param array<string,string> $aliases
     */
    public function __construct(array $aliases = [])
    {
        self::aliasAssertNoCycles($aliases);
        $this->aliases = $aliases;
    }

    /**
     * @psalm-return array<string,string>
     */
    public function aliasesArray(): array
    {
        return $this->aliases;
    }

    public function aliasExists(string $alias): bool
    {
        return isset($this->aliases[$alias]);
    }

    /**
     * @throws CyclicAliasException
     */
    public function aliasSet(string $alias, string $target): void
    {
        self::aliasAssertNoCycles($this->aliases, $alias, $target);
        $this->aliases[$alias] = $target;
    }

    public function aliasUnset(string $alias): void
    {
        unset($this->aliases[$alias]);
    }

    /**
     * @throws NotFoundException
     */
    public function aliasGet(string $alias): string
    {
        if (!isset($this->aliases[$alias])) {
            throw new NotFoundException(sprintf('alias %s not found', var_export($alias, true)));
        }

        return $this->aliases[$alias];
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function aliasResolve(string $alias): string
    {
        $target = $alias;
        while (null !== ($next = $this->aliases[$target] ?? null)) {
            $target = $next;
        }

        return $target;
    }

    /**
     * @psalm-param array<string,string> $aliases
     *
     * @psalm-return ?list<string>
     */
    private static function aliasDetectCycle(array $aliases, string $alias, ?string $target): ?array
    {
        $path = [$alias];

        $target ??= ($aliases[$alias] ?? null);

        for ($curr = $target; null !== $curr; $curr = $aliases[$curr] ?? null) {
            if (in_array($curr, $path, true)) {
                $path[] = $curr;

                return $path;
            }
            $path[] = $curr;
        }

        return null;
    }

    /**
     * @psalm-param array<string,string> $aliases
     *
     * @throws CyclicAliasException
     */
    private static function aliasAssertNoCycles(array $aliases, string $alias = null, string $target = null): void
    {
        if (null !== $alias) {
            if (null !== ($cycle = self::aliasDetectCycle($aliases, $alias, $target))) {
                $cycleStr = implode(' -> ', array_map(fn (string $s): string => var_export($s, true), $cycle));

                throw new CyclicAliasException(sprintf('cyclic alias detected: %s', $cycleStr));
            }

            return;
        }

        foreach ($aliases as $alias => $target) {
            self::aliasAssertNoCycles($aliases, $alias, $target);
        }
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait InstancesTrait
{
    /**
     * @psalm-var array<class-string,object>
     */
    private array $instances;

    /**
     * @psalm-return array<class-string,object>
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * Returns true if instance of given *$type* exists.
     */
    public function instanceExists(string $type): bool
    {
        return isset($this->instances[$type]);
    }

    /**
     * Registers singleton for a given *$type*.
     */
    public function instanceSet(string $type, object $instance): void
    {
        self::instanceAssertNoCycles($this->instances, $instance, $target);
        $this->instances[$instance] = $target;
    }

    /**
     * Unregister instance registered under *$type*.
     */
    public function instanceUnset(string $type): void
    {
        unset($this->instances[$type]);
    }

    /**
     * Returns direct target assigned to instance.
     *
     * @throws NotFoundException
     */
    public function instanceGet(string $instance): string
    {
        if (!isset($this->instances[$instance])) {
            throw new NotFoundException(sprintf('instance %s not found', var_export($instance, true)));
        }

        return $this->instances[$instance];
    }

    /**
     * Resolves instance recursively.
     *
     * @throws NotFoundExceptionInterface
     */
    public function instanceResolve(string $instance): string
    {
        $target = $instance;
        while (null !== ($next = $this->instances[$target] ?? null)) {
            $target = $next;
        }

        return $target;
    }

    /**
     * @psalm-param array<string,string> $instances
     *
     * @psalm-return ?list<string>
     */
    private static function instanceDetectCycle(array $instances, string $instance, ?string $target): ?array
    {
        $path = [$instance];

        $target ??= ($instances[$instance] ?? null);

        for ($curr = $target; null !== $curr; $curr = $instances[$curr] ?? null) {
            if (in_array($curr, $path, true)) {
                $path[] = $curr;

                return $path;
            }
            $path[] = $curr;
        }

        return null;
    }

    /**
     * @psalm-param array<string,string> $instances
     *
     * @throws CyclicAliasException
     */
    private static function instanceAssertNoCycles(array $instances, string $instance = null, string $target = null): void
    {
        if (null !== $instance) {
            if (null !== ($cycle = self::instanceDetectCycle($instances, $instance, $target))) {
                $cycleStr = implode(' -> ', array_map(fn (string $s): string => var_export($s, true), $cycle));

                throw new CyclicAliasException(sprintf('cyclic instance detected: %s', $cycleStr));
            }

            return;
        }

        foreach ($instances as $instance => $target) {
            self::instanceAssertNoCycles($instances, $instance, $target);
        }
    }
}

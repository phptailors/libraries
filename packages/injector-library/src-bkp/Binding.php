<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-immutable
 *
 * @psalm-template ClosureType of \Closure
 *
 * @psalm-template-inherits BindingInterface<ClosureType>
 */
final class Binding implements BindingInterface
{
    private \Closure $closure;

    private bool $shared;

    /**
     * @psalm-param ClosureType $closure
     */
    public function __construct(\Closure $closure, bool $shared = false)
    {
        $this->closure = $closure;
        $this->shared = $shared;
    }

    public function getClosure(): \Closure
    {
        return $this->closure;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }
}

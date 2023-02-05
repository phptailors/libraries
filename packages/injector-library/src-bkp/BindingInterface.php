<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template ClosureType of \Closure
 */
interface BindingInterface
{
    /**
     * @psalm-return ClosureType
     */
    public function getClosure(): \Closure;

    public function isShared(): bool;
}

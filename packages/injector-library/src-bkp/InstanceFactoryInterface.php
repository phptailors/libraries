<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type CallbackType \Closure(ResolverInterface):mixed
 */
interface InstanceFactoryInterface
{
    public function __invoke(ResolverInterface $resolver): mixed;

    /**
     * @psalm-return CallbackType
     */
    public function callback(): \Closure;

    public function shared(): bool;
}

<?php

declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @psalm-import-type CallbackType from InstanceFactoryInterface
 */
final class InstanceFactoryWrapper implements InstanceFactoryInterface
{
    /**
     * @psalm-var CallbackType
     */
    private \Closure $callback;
    private bool $shared;

    /**
     * @psalm-param CallbackType $callback
     */
    public function __construct(\Closure $callback, bool $shared)
    {
        $this->callback = $callback;
        $this->shared = $shared;
    }

    public function __invoke(ResolverInterface $resolver): mixed
    {
        return $this->callback->__invoke($resolver);
    }

    /**
     * @psalm-return CallbackType
     */
    public function callback(): \Closure
    {
        return $this->callback;
    }

    public function shared(): bool
    {
        return $this->shared;
    }
}

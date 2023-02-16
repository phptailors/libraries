<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

abstract class AbstractFactoryBase
{
    /**
     * @psalm-readonly
     */
    private bool $shared;

    public function __construct(bool $shared = false)
    {
        $this->shared = $shared;
    }

    /**
     * Returns true if the created instance shall be shared (is a singleton).
     *
     * @psalm-mutation-free
     */
    public function shared(): bool
    {
        return $this->shared;
    }
}

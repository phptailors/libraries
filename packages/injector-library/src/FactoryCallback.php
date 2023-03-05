<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template TValue
 *
 * @template-implements FactoryInterface<TValue>
 */
final class FactoryCallback extends AbstractFactoryBase implements FactoryInterface
{
    /**
     * @psalm-var \Closure(ResolverInterface):TValue
     *
     * @psalm-readonly
     */
    private \Closure $callback;

    /**
     * @psalm-param \Closure(ResolverInterface):TValue $callback
     */
    public function __construct(\Closure $callback, bool $shared = false)
    {
        parent::__construct($shared);
        $this->callback = $callback;
    }

    /**
     * @psalm-mutation-free
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @psalm-return TValue
     */
    public function create(ResolverInterface $resolver): mixed
    {
        return $this->callback->__invoke($resolver);
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template ReturnType
 *
 * @template-implements FactoryInterface<ReturnType>
 */
final class FactoryCallback implements FactoryInterface
{
    /**
     * @psalm-var \Closure(ResolverInterface):ReturnType
     *
     * @psalm-readonly
     */
    private \Closure $callback;

    /**
     * @psalm-param \Closure(ResolverInterface):ReturnType $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @psalm-immutable
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @psalm-return ReturnType
     */
    public function create(ResolverInterface $resolver): mixed
    {
        return $this->callback->__invoke($resolver);
    }
}

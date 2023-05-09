<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class CallbackItem implements ItemInterface
{
    /**
     * @psalm-readonly
     *
     * @psalm-var \Closure(ResolverInterface):mixed
     */
    private readonly \Closure $callback;

    /**
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @psalm-return \Closure(ResolverInterface):mixed
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(ResolverInterface $resolver): mixed
    {
        return ($this->callback)($resolver);
    }
}

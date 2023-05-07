<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class SingletonItem implements ItemInterface
{
    /**
     * @psalm-readonly
     *
     * @psalm-var \Closure(ResolverInterface):object
     */
    private \Closure $callback;

    private ?object $instance;

    /**
     * @psalm-param \Closure(ResolverInterface):object $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
        $this->instance = null;
    }

    /**
     * @psalm-return \Closure(ResolverInterface):object
     */
    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    public function getInstance(): ?object
    {
        return $this->instance;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(ResolverInterface $resolver): object
    {
        if (null === $this->instance) {
            $this->instance = ($this->callback)($resolver);
        }

        return $this->instance;
    }
}

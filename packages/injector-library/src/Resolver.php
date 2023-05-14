<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * This resolver, once used, should not be reused.
 */
final class Resolver implements ResolverInterface
{
    /**
     * @psalm-readonly
     */
    private readonly ItemContainerInterface $container;

    /**
     * @psalm-var array<string,mixed>
     */
    private array $backtrace;

    public function __construct(ItemContainerInterface $container)
    {
        $this->container = $container;
        $this->backtrace = [];
    }

    public function getContainer(): ItemContainerInterface
    {
        return $this->container;
    }

    /**
     * @return array<string,mixed>
     */
    public function getBacktrace(): array
    {
        return $this->backtrace;
    }

    /**
     * @param string $id identifier of the entry to look for
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed
    {
        try {
            if (isset($this->backtrace[$id])) {
                throw CircularDependencyException::fromBacktrace(array_keys($this->backtrace), $id);
            }

            $this->backtrace[$id] = true;

            /** @psalm-var mixed */
            $value = $this->container->getItem($id)->resolve($this);
        } finally {
            unset($this->backtrace[$id]);
        }

        return $value;
    }

    /**
     * @psalm-template T of object
     *
     * @psalm-param class-string<T> $class
     *
     * @psalm-return T
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws TypeExceptionInterface
     */
    public function resolveClass(string $class): object
    {
        $instance = $this->resolve($class);
        if (!is_a($instance, $class)) {
            throw TypeException::fromTypeAndValue($class, $instance);
        }
        // For non-object instace the return statement will throw TypeError.
        return $instance;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * This Resolver, once used, should not be reused.
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
    private array $resolving;

    public function __construct(ItemContainerInterface $container)
    {
        $this->container = $container;
        $this->resolving = [];
    }

    public function getContainer(): ItemContainerInterface
    {
        return $this->container;
    }

    /**
     * @return array<string,mixed>
     */
    public function getResolving(): array
    {
        return $this->resolving;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id identifier of the entry to look for
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed
    {
        try {
            if (isset($this->resolving[$id])) {
                throw CircularDependencyException::fromBacktrace(array_keys($this->resolving), $id);
            }

            $this->resolving[$id] = true;

            /** @psalm-var mixed */
            $value = $this->container->getItem($id)->resolve($this);
        } finally {
            unset($this->resolving[$id]);
        }

        return $value;
    }
}

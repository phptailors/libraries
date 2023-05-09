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
}

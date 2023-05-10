<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TContents array{
 *      aliases?: array<string,string>,
 *      instances?: array<string,mixed>,
 *      factories?: array<string,\Closure(ResolverInterface):mixed>,
 *      singletons?: array<string,\Closure(ResolverInterface):mixed>
 * }
 */
final class Container implements ContainerInterface, ItemContainerInterface, ResolverInterface
{
    /**
     * @psalm-var array{'aliases', 'instances', 'factories', 'singletons'}
     */
    private const CONTENTS = [
        'aliases',
        'instances',
        'factories',
        'singletons',
    ];

    /**
     * @psalm-var TContents
     */
    private array $contents;

    /**
     * @psalm-readonly
     */
    private readonly ResolverFactoryInterface $resolverFactory;

    /**
     * @psalm-param TContents $contents
     */
    public function __construct(array $contents = [], ResolverFactoryInterface $resolverFactory = null)
    {
        $this->contents = $contents;
        $this->resolverFactory = $resolverFactory ?? new ResolverFactory();
    }

    /**
     * Returns the container contents array (for testing, tooling, debuging).
     *
     * @psalm-return TContents
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * Returns the containers resolver factory.
     */
    public function getResolverFactory(): ResolverFactoryInterface
    {
        return $this->resolverFactory;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id identifier of the entry to look for
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get(string $id): mixed
    {
        return $this->resolve($id);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(string $id): mixed
    {
        return $this->resolverFactory->getResolver($this)->resolve($id);
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
        return $this->resolverFactory->getResolver($this)->resolveClass($class);
    }

    /**
     * Returns true if the container can return an entry for the given
     * identifier. Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id identifier of the entry to look for
     */
    public function has(string $id): bool
    {
        // FIXME: ensure exceptions are thrown as specified in PSR-11
        // For example, an EXISTING, but UNRESOLVABLE alias $id, should yield
        // ``has($id) === true``, but ``get($id)`` must throw
        // ``ContainerExceptionInterface``, not ``NotFoundExceptionInterface``.
        return $this->hasItem($id);
    }

    public function hasItem(string $id): bool
    {
        foreach (self::CONTENTS as $key) {
            if (isset($this->contents[$key]) && array_key_exists($id, $this->contents[$key])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function getItem(string $id): ItemInterface
    {
        if (isset($this->contents['aliases'][$id])) {
            return new AliasItem($this->contents['aliases'][$id]);
        }

        if (isset($this->contents['instances']) && array_key_exists($id, $this->contents['instances'])) {
            return new InstanceItem($this->contents['instances'][$id]);
        }

        if (isset($this->contents['factories'][$id])) {
            return new CallbackItem($this->contents['factories'][$id]);
        }

        if (isset($this->contents['singletons'][$id])) {
            return new CallbackItem($this->singletonCallback($id, $this->contents['singletons'][$id]));
        }

        throw new NotFoundException(sprintf('%s not found', var_export($id, true)));
    }

    /**
     * Remove item stored under key $id from the container.
     */
    public function unsetItem(string $id): void
    {
        foreach (self::CONTENTS as $key) {
            unset($this->contents[$key][$id]);
        }
    }

    /**
     * Create $alias for $target.
     */
    public function alias(string $alias, string $target): void
    {
        $this->contents['aliases'][$alias] = $target;
        $this->unsetItemExceptFor('aliases', $alias);
    }

    /**
     * Store the $instance under key $id.
     */
    public function instance(string $id, mixed $instance): void
    {
        $this->contents['instances'][$id] = $instance;
        $this->unsetItemExceptFor('instances', $id);
    }

    public function factory(string $id, \Closure $callback): void
    {
        $this->contents['factories'][$id] = $callback;
        $this->unsetItemExceptFor('factories', $id);
    }

    /**
     * Lazily create single instance and store under key $id.
     *
     * @psalm-param \Closure(ResolverInterface):mixed $callback
     */
    public function singleton(string $id, \Closure $callback): void
    {
        $this->contents['singletons'][$id] = $callback;
        $this->unsetItemExceptFor('singletons', $id);
    }

    /**
     * @psalm-template T of mixed
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return \Closure(ResolverInterface):T
     */
    private function singletonCallback(string $id, \Closure $callback): \Closure
    {
        return function (ResolverInterface $resolver) use ($id, $callback): mixed {
            $instance = $callback($resolver);
            $this->instance($id, $instance);

            return $instance;
        };
    }

    private function unsetItemExceptFor(string $type, string $id): void
    {
        foreach (self::CONTENTS as $key) {
            if ($key !== $type) {
                unset($this->contents[$key][$id]);
            }
        }
    }
}

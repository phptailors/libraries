<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TContents array{
 *      aliases?: array<string,string>,
 *      instances?: array<string,object>,
 *      bindings?: array<string,\Closure(ResolverInterface):mixed>,
 *      singletons?: array<string,\Closure(ResolverInterface):object>
 * }
 */
final class Container implements ContainerInterface, ResolverInterface
{
    /**
     * @psalm-var array{
     *  aliases: class-string<AliasItem>,
     *  instances: class-string<InstanceItem>,
     *  bindings: class-string<BindingItem>,
     *  singletons: class-string<SingletonItem>,
     * }
     */
    private const ITEM_CLASSES = [
        'aliases'    => AliasItem::class,
        'instances'  => InstanceItem::class,
        'bindings'   => BindingItem::class,
        'singletons' => SingletonItem::class,
    ];

    /** @psalm-var TContents */
    private array $contents;

    private ResolverFactoryInterface $resolverFactory;

    /**
     * @psalm-param TContents $contents
     */
    public function __construct(array $contents = [], ResolverFactoryInterface $resolverFactory = null)
    {
        $this->contents = $contents;
        $this->resolverFactory = $resolverFactory ?? new ResolverFactory();
    }

    /**
     * @psalm-return TContents
     */
    public function getContents(): array
    {
        return $this->contents;
    }

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
        // FIXME: implement deeper inspection (walk through aliases, etc.).
        return $this->hasItem($id);
    }

    public function hasItem(string $id): bool
    {
        foreach (self::ITEM_CLASSES as $key => $_) {
            if (isset($this->contents[$key][$id])) {
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
        foreach (self::ITEM_CLASSES as $key => $class) {
            if (isset($this->contents[$key][$id])) {
                return new $class($this->contents[$key][$id]);
            }
        }

        throw new NotFoundException(sprintf('%s not found', var_export($id, true)));
    }

    /**
     * Remove item stored under key $id from the container.
     */
    public function unsetItem(string $id): void
    {
        foreach (self::ITEM_CLASSES as $key => $_) {
            if (isset($this->contents[$key][$id])) {
                unset($this->contents[$key][$id]);
            }
        }
    }

    /**
     * Create $alias for $target.
     */
    public function alias(string $alias, string $target): void
    {
        $this->contents['aliases'][$alias] = $target;
    }

    /**
     * Store the $instance under key $id.
     */
    public function instance(string $id, object $instance): void
    {
        $this->contents['instances'][$id] = $instance;
    }

    public function bind(string $id, \Closure $callback): void
    {
        $this->contents['bindings'][$id] = $callback;
    }

    /**
     * Lazily create single instance and store under key $id.
     *
     * @psalm-param \Closure(ResolverInterface):object $callback
     */
    public function singleton(string $id, \Closure $callback): void
    {
        $this->contents['singletons'][$id] = $callback;
    }
}

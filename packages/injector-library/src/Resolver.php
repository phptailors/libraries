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
     * @psalm-param array<string,mixed> $backtrace
     */
    public function __construct(private readonly ItemContainerInterface $container, private array $backtrace = [])
    {
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

//    /**
//     * Invoke *$callback* with a ResolverInterface configured to work in
//     * context of a *$class*. Suitable for *$callback*s that set properties on
//     * objects.
//     *
//     * @psalm-template T
//     *
//     * @psalm-param class-string<T> $class
//     * @psalm-param \Closure(ResolverInterface):T $callback
//     *
//     * @psalm-return T
//     */
//    public function class(string $class, \Closure $callback): mixed
//    {
//    }
//
//    /**
//     * Invoke *$callback* with a ResolverInterface configured to work in
//     * context of a *$class* constructor. Suitable for *$callbacks* that create
//     * instances with the **new** operator.
//     *
//     * @psalm-template T
//     *
//     * @psalm-param class-string<T> $class
//     * @psalm-param \Closure(ResolverInterface):T $callback
//     *
//     * @psalm-return T
//     */
//    public function new(string $class, \Closure $callback): object
//    {
//    }
//
//    /**
//     * Invoke *$callback* with a ResolverInterface configured to work in
//     * context of a *$class::$method*. Suitable for *$callbacks* that create
//     * instances with a static factory methods.
//     *
//     * @psalm-template T
//     *
//     * @psalm-param \Closure(ResolverInterface):T $callback
//     *
//     * @psalm-return T
//     */
//    public function method(string $class, string $method, \Closure $callback): mixed
//    {
//    }
//
//    /**
//     * Invoke *$callback* with a ResolverInterface configured to work in
//     * context of a *$function*. Suitable for callbacks that create instances
//     * using plain functions.
//     *
//     * @psalm-template T
//     *
//     * @psalm-param \Closure(ResolverInterface):T $callback
//     *
//     * @psalm-return T
//     */
//    public function function(string $function, \Closure $callback): mixed
//    {
//    }
}

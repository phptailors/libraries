<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

interface InjectionInterface
{
    /**
     * Invoke *$callback* with a ResolverInterface configured to work in
     * context of a *$class*. Suitable for *$callback*s that set properties on
     * objects.
     *
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function class(string $class, \Closure $callback): mixed;

    /**
     * Invoke *$callback* with a ResolverInterface configured to work in
     * context of a *$class* constructor. Suitable for *$callbacks* that create
     * instances with the **new** operator.
     *
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function new(string $class, \Closure $callback): object;

    /**
     * Invoke *$callback* with a ResolverInterface configured to work in
     * context of a *$class::$method*. Suitable for *$callbacks* that create
     * instances with a static factory methods.
     *
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function method(string $class, string $method, \Closure $callback): mixed;

    /**
     * Invoke *$callback* with a ResolverInterface configured to work in
     * context of a *$function*. Suitable for callbacks that create instances
     * using plain functions.
     *
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function function(string $function, \Closure $callback): mixed;
}

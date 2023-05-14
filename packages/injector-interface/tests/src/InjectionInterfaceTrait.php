<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this trait is not covered by backward compatiblity promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
trait InjectionInterfaceTrait
{
    public mixed $class;

    public mixed $new;

    public mixed $method;

    public mixed $function;

    /**
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function class(string $class, \Closure $callback): mixed
    {
        return $this->class;
    }

    /**
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function new(string $class, \Closure $callback): object
    {
        return $this->new;
    }

    /**
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function method(string $class, string $method, \Closure $callback): mixed
    {
        return $this->method;
    }

    /**
     * @psalm-template T
     *
     * @psalm-param \Closure(ResolverInterface):T $callback
     *
     * @psalm-return T
     */
    public function function(string $function, \Closure $callback): mixed
    {
        return $this->function;
    }
}

// vim: syntax=php sw=4 ts=4 et:

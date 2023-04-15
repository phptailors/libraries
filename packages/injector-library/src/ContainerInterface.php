<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ContainerInterface
{
    public function lookupAlias(string $key, ContextInterface $context): ?string;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    public function lookupInstance(string $class, ContextInterface $context): ?object;

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function lookupFactory(string $class, ContextInterface $context): ?FactoryInterface;
}

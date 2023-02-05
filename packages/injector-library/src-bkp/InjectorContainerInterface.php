<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface InjectorContainerInterface
{
    public function hasClass(string $class): bool;

    public function hasNamespace(string $namespace): bool;

    public function getGlobal(): ScopeContainerInterface;

    /**
     * @throws NotFoundException
     */
    public function getClass(string $class): ScopeContainerInterface;

    /**
     * @throws NotFoundException
     */
    public function getNamespace(string $namespace): ScopeContainerInterface;
}

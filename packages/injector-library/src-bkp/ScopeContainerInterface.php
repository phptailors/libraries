<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward-compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ScopeContainerInterface
{
    /**
     * Returns true if **$alias** is defined in the container.
     */
    public function hasAlias(string $alias): bool;

    /**
     * Returns true if a shared instance (singleton) of the specified **$type**
     * is defined in container.
     */
    public function hasInstance(string $type): bool;

    /**
     * Returns true if the variable specified by **$name** has defined binding
     * in the container.
     */
    public function hasVariable(string $name): bool;

    /**
     * Returns true if the parameter of **$function** specified by **$param**
     * has defined binding in the container. The **$param** may specify the
     * parameter by its name (string) or position on the parameter list (int).
     */
    public function hasParameter(string $function, string|int $param): bool;

    /**
     * Returns true if **$type** has defined binding in the container.
     */
    public function hasType(string $type): bool;

    /**
     * @throws NotFoundException
     */
    public function getAlias(string $key): string;

    /**
     * @throws NotFoundException
     */
    public function getInstance(string $type): mixed;

    /**
     * @throws NotFoundException
     */
    public function getVariable(string $name): \Closure;

    /**
     * @throws NotFoundException
     */
    public function getParameter(string $function, string|int $param): mixed;

    /**
     * @throws NotFoundException
     */
    public function getType(string $type): mixed;

    /**
     * Removes **$alias** from the container.
     */
    public function unsetAlias(string $alias): void;

    /**
     * Removes shared instance of the given **$type** from the container.
     */
    public function unsetInstance(string $type): void;

    /**
     * Removes binding for the variable specified by **$name** from the container.
     */
    public function unbindVariable(string $name): void;

    /**
     * Removes binding for the **$function** parameter **$param** from the container.
     */
    public function unbindParameter(string $function, string|int $param): void;

    /**
     * Removes binding for the specified **$type** from the container.
     */
    public function unbindType(string $type): void;

    /**
     * Defines (or redefined) alias.
     */
    public function setAlias(string $alias, string $target): void;

    /**
     * Binds an instance ($value) to **$type**.
     */
    public function setInstance(string $type, mixed $value): void;

    /**
     * Defines binding for a variable specified by **$name**.
     */
    public function bindVariable(string $name, \Closure $closure): void;

    /**
     * Defines binding for a **$function** parameter specified by **$param**.
     */
    public function bindParameter(string $function, string|int $param, \Closure $closure): void;

    /**
     * Defines binding for a **$type**.
     */
    public function bindType(string $type, mixed $value): void;
}

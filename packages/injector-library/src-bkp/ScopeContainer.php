<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type ContentsType = array{
 *   aliases?:      array<string,string>,
 *   instances?:    array<stirng,mixed>,
 *   variables?:    array<string,BindingInterface>,
 *   parameters?:   array<string,array<array-key,BindingInterface>>
 *   types?:        array<string,BindingInterface>
 * }
 */
final class ScopeContainer implements ScopeContainerInterface
{
    /**
     * A string => string map of aliases.
     *
     * @psalm-var array<string,string>
     */
    private array $aliases;

    /**
     * Shared instances (singletons).
     *
     * @psalm-var array<string,mixed>
     */
    private array $instances;

    /**
     * Binding directly to variables (properties in case of a class).
     *
     * @psalm-var array<string,BindingInterface>
     */
    private array $variables;

    /**
     * Binding directly to function parameters (by name or .
     *
     * @psalm-var array<string,array<array-key,BindingInterface>>
     */
    private array $parameters;

    /**
     * Binding to type-hinted variables and parameters.
     *
     * @psalm-var array<string,BindingInterface>
     */
    private array $types;

    /**
     * @psalm-param ContentsType $contents
     */
    public function __construct(array $contents = [])
    {
        $this->aliases = $contents['aliases'] ?? [];
        $this->instances = self::arrayNormalizeKeys($contents['instances'] ?? []);
        $this->variables = $contents['variables'] ?? [];
        $this->parameters = self::arrayNormalizeKeys($contents['parameters'] ?? []);
        $this->types = self::arrayNormalizeKeys($contents['types'] ?? []);
    }

    /**
     * @psalm-return array<string,string>
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @psalm-return <string,mixed>
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * @psalm-return <string,\Closure>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @psalm-return <string,array<array-key,\Closure>>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @psalm-return <string,\Closure>
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAlias(string $alias): bool
    {
        return isset($this->aliases[$alias]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasInstance(string $type): bool
    {
        $key = self::normalizeKey($type);

        return isset($this->instances[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasVariable(string $name): bool
    {
        return isset($this->variables[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter(string $function, string|int $param): bool
    {
        $key = self::normalizeKey($function);

        return isset($this->parameters[$key][$param]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasType(string $type): bool
    {
        $key = self::normalizeKey($type);

        return isset($this->types[$key]);
    }

    /**
     * {@inhertitdoc}.
     *
     * @throws NotFoundException
     */
    public function getAlias(string $key): string
    {
        return self::getArrayItem($this->aliases, $key, 'alias', $key);
    }

    /**
     * @throws NotFoundException
     */
    public function getInstance(string $type): mixed
    {
        $key = self::normalizeKey($type);

        return self::getArrayItem($this->instances, $key, 'shared instance', $type);
    }

    /**
     * @throws NotFoundException
     */
    public function getVariable(string $name): \Closure
    {
        return self::getArrayItem($this->variables, $name, 'binding for variable', '$'.$name);
    }

    /**
     * @throws NotFoundException
     */
    public function getParameter(string $function, string|int $param): \Closure
    {
        $key = self::normalizeKey($function);
        $params = self::getArrayItem($this->parameters, $key, 'bindings for parameters of function', $function);
        $userKey = is_string($param) ? ('$'.$param) : $param;

        return self::getArrayItem($params, $param, 'binding for function '.$function.'() parameter', $userKey);
    }

    /**
     * @throws NotFoundException
     */
    public function getType(string $type): \Closure
    {
        $key = self::normalizeKey($type);

        return self::getArrayItem($this->types, $key, 'binding for type', $type);
    }

    public function unsetAlias(string $alias): void
    {
        unset($this->aliases[$alias]);
    }

    public function unsetInstance(string $type): void
    {
        $key = self::normalizeKey($type);
        unset($this->instances[$key]);
    }

    public function unbindVariable(string $name): void
    {
        unset($this->variables[$name]);
    }

    public function unbindParameter(string $function, string|int $param): void
    {
        $key = self::normalizeKey($function);
        if (isset($this->parameters[$key])) {
            unset($this->parameters[$key][$param]);
            if (empty($this->parameters[$key])) {
                unset($this->parameters[$key]);
            }
        }
    }

    public function unbindType(string $type): void
    {
        $key = self::normalizeKey($type);
        unset($this->types[$key]);
    }

    public function setAlias(string $alias, string $target): void
    {
        $this->aliases[$alias] = $target;
    }

    public function setInstance(string $type, mixed $value): void
    {
        $key = self::normalizeKey($type);
        $this->instances[$key] = $value;
    }

    public function bindVariable(string $name, \Closure $closure): void
    {
        $this->variables[$name] = $closure;
    }

    public function bindParameter(string $function, string|int $param, \Closure $closure): void
    {
        $key = self::normalizeKey($function);
        $this->parameters[$key][$param] = $closure;
    }

    public function bindType(string $type, mixed $value): void
    {
        $key = self::normalizeKey($type);
        $this->types[$key] = $value;
    }

    /**
     * @throws NotFoundException
     *
     * @pslam-template T
     *
     * @psalm-param array<array-key, T> $array
     *
     * @psalm-return T
     */
    private static function getArrayItem(array $array, string|int $key, string $what, string|int $userKey): mixed
    {
        if (!isset($array[$key])) {
            if (is_string($userKey)) {
                $message = sprintf('%s %s not found', $what, var_export($userKey, true));
            } else {
                $message = sprintf('%s #%d not found', $what, $userKey);
            }

            throw new NotFoundException($message);
        }

        return $array[$key];
    }

    private static function normalizeKey(string $name): string
    {
        return strtolower(ltrim($name, '\\'));
    }

    /**
     * @psalm-param array<string, mixed> $array
     */
    private static function arrayNormalizeKeys(array $array): array
    {
        return array_combine(array_map([self::class, 'normalizeKey'], array_keys($array)), array_values($array));
    }
}

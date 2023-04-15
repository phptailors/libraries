<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 * @psalm-type TInstances array{
 *      class?:     array<string,class-string-map<T,T>>,
 *      namespace?: array<string,class-string-map<T,T>>,
 *      function?:  array<string,class-string-map<T,T>>,
 *      method?:    array<string,array<string, class-string-map<T,T>>>,
 *      global?:    class-string-map<T,T>
 * }
 * @psalm-type TFactories array{
 *      class?:     array<string,class-string-map<T,FactoryInterface<T>>>,
 *      namespace?: array<string,class-string-map<T,FactoryInterface<T>>>,
 *      function?:  array<string,class-string-map<T,FactoryInterface<T>>>,
 *      method?:    array<string,array<string, class-string-map<T,FactoryInterface<T>>>>,
 *      global?:    class-string-map<T,FactoryInterface<T>>
 * }
 */
final class Container implements ContainerInterface
{
    /**
     * @psalm-var TAliases
     */
    private array $aliases;

    /**
     * @psalm-var TInstances
     */
    private array $instances;

    /**
     * @psalm-var TFactories
     */
    private array $factories;

    /**
     * @psalm-param TAliases $aliases
     * @psalm-param TInstances $instances
     * @psalm-param TFactories $factories
     */
    public function __construct(array $aliases = [], array $instances = [], array $factories = [])
    {
        $this->aliases = $aliases;
        $this->instances = $instances;
        $this->factories = $factories;
    }

    public function lookupAlias(string $key, ContextInterface $context): ?string
    {
        foreach ($context->getLookupScopes() as $lookup) {
            if ($lookup->lookupScopedArray($this->aliases, $key, $retval)) {
                return $retval;
            }
        }

        return null;
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?TObj
     */
    public function lookupInstance(string $class, ContextInterface $context): ?object
    {
        foreach ($context->getLookupScopes() as $lookup) {
            if (null !== ($instance = $lookup->lookupScopedInstanceMap($this->instances, $class))) {
                return $instance;
            }
        }

        return null;
    }

    /**
     * @psalm-template TObj of object
     *
     * @psalm-param class-string<TObj> $class
     *
     * @psalm-return ?FactoryInterface<TObj>
     */
    public function lookupFactory(string $class, ContextInterface $context): ?FactoryInterface
    {
        foreach ($context->getLookupScopes() as $lookup) {
            if (null !== ($factory = $lookup->lookupScopedFactoryMap($this->factories, $class))) {
                return $factory;
            }
        }

        return null;
    }
}

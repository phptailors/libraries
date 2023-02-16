<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Scope implements ScopeInterface, AliasesWrapperInterface, InstancesWrapperInterface, FactoriesWrapperInterface
{
    use AliasesWrapperTrait;
    use InstancesWrapperTrait;
    use FactoriesWrapperTrait;

    private AliasesInterface $aliases;
    private InstancesInterface $instances;
    private FactoriesInterface $factories;

    public function __construct(
        AliasesInterface $aliases,
        InstancesInterface $instances,
        FactoriesInterface $factories
    ) {
        $this->aliases = $aliases;
        $this->instances = $instances;
        $this->factories = $factories;
    }

    public function getAliases(): AliasesInterface
    {
        return $this->aliases;
    }

    public function getInstances(): InstancesInterface
    {
        return $this->instances;
    }

    public function getFactories(): FactoriesInterface
    {
        return $this->factories;
    }
}

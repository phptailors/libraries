<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class Scope implements ScopeInterface, AliasesInterface, AliasesWrapperInterface, InstancesInterface, InstancesWrapperInterface
{
    use AliasesWrapperTrait;
    use InstancesWrapperTrait;

    private AliasesInterface $aliases;
    private InstancesInterface $instances;

    public function __construct(AliasesInterface $aliases, InstancesInterface $instances)
    {
        $this->aliases = $aliases;
        $this->instances = $instances;
    }

    public function getAliases(): AliasesInterface
    {
        return $this->aliases;
    }

    public function getInstances(): InstancesInterface
    {
        return $this->instances;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * Implementation of the ResolverInterface.
 *
 * @internal This interface is not covered by backward compatibility promise
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type AliasesArray = array<string,string>
 * @psalm-type InstancesArray = array<string, mixed>
 * @psalm-type BindingsArray = array<string, InstanceFactoryInterface>
 * @psalm-type ContextualBindingsArray = array<string, BindingsArray>
 */
interface ResolverContainerInterface
{
    /**
     * @psalm-return AliasesArray
     */
    public function getAliases(): array;

    /**
     * @psalm-return InstancesArray
     */
    public function getInstances(): array;

    /**
     * @psalm-return BindingsArray
     */
    public function getBindings(): array;

    /**
     * @psalm-return ContextualBindingsArray
     */
    public function getContextualBindings(): array;
}

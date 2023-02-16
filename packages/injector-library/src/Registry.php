<?php

namespace Tailors\Lib\Injector;

final class Registry implements RegistryInterface, NamespacesWrapperInterface
{
    use NamespacesWrapperTrait;

    private ScopeInterface $globalScope;
    private NamespacesInterface $namespaces;

    public function __construct(ScopeInterface $globalScope, NamespacesInterface $namespaces)
    {
        $this->globalScope = $globalScope;
        $this->namespaces = $namespaces;
    }

    public function getNamespaces(): NamespacesInterface
    {
        return $this->namespaces;
    }

    public function getGlobalScope(): ScopeInterface
    {
        return $this->globalScope;
    }
}

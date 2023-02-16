<?php

namespace Tailors\Lib\Injector;


interface RegistryInterface extends NamespacesInterface
{
    public function getGlobalScope(): ScopeInterface;
}

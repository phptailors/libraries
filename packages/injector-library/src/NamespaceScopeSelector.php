<?php

namespace Tailors\Lib\Injector;

final class NamespaceScopeSelector implements ScopeSelectorInterface
{
    private string $namespace;

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @psalm-template TUnscopedArray of array
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     */
    public function hasUnscopedArray(ScopedArrayInterface $array): bool
    {
        $scoped = $array->getScopedArrayRef();
        if (!array_key_exists('NamespaceScope', $scoped)) {
            return false;
        }
        return array_key_exists($namespace, $scoped['NamespaceScope']);
    }

    /**
     * @psalm-template TUnscopedArray
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     * @psalm-return TUnscopedArray
     */
    public function &getUnscopedArrayRef(ScopedArrayInterface $array): array
    {
        $scoped = $array->getScopedArrayRef();
        return $scoped['NamespaceScope'][$this->namespace];
    }
}

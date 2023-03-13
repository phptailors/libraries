<?php

namespace Tailors\Lib\Injector;

final class ClassScopeSelector implements ScopeSelectorInterface
{
    private string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * @psalm-template TUnscopedArray of array
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     */
    public function hasUnscopedArray(ScopedArrayInterface $array): bool
    {
        $scoped = $array->getScopedArrayRef();
        if (!array_key_exists('ClassScope', $scoped)) {
            return false;
        }
        return array_key_exists($class, $scoped['ClassScope']);
    }

    /**
     * @psalm-template TUnscopedArray
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     * @psalm-return TUnscopedArray
     */
    public function &getUnscopedArrayRef(ScopedArrayInterface $array): array
    {
        $scoped = $array->getScopedArrayRef();
        return $scoped['ClassScope'][$this->class];
    }
}

<?php

namespace Tailors\Lib\Injector;

final class GlobalScopeSelector implements ScopeSelectorInterface
{
    /**
     * @psalm-template TUnscopedArray of array
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     */
    public function hasUnscopedArray(ScopedArrayInterface $array): bool
    {
        return array_key_exists('GlobalScope', $array->getScopedArrayRef());
    }

    /**
     * @psalm-template TUnscopedArray
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     * @psalm-return TUnscopedArray
     */
    public function &getUnscopedArrayRef(ScopedArrayInterface $array): array
    {
        $scoped = $array->getScopedArrayRef();
        return $scoped['GlobalScope'];
    }
}

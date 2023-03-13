<?php

namespace Tailors\Lib\Injector;

interface ScopeSelectorInterface
{
    /**
     * @psalm-template TUnscopedArray of array
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     */
    public function hasUnscopedArray(ScopedArrayInterface $array): bool;

    /**
     * @psalm-template TUnscopedArray
     * @psalm-param ScopedArrayInterface<TUnscopedArray> $array
     * @psalm-return TUnscopedArray
     */
    public function &getUnscopedArrayRef(ScopedArrayInterface $array): array;
}

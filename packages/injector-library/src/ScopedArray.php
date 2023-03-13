<?php

namespace Tailors\Lib\Injector;


/**
 * @psalm-template TUnscopedArray of array<string,mixed>
 * @template-implements ScopedArrayInterface<TUnscopedArray>
 * @psalm-import-type TScopedArray from ScopedArrayInterface<TUnscopedArray>
 * @psalm-import-type TScopeKey from ScopedArrayInterface<TUnscopedArray>
 */
abstract class AbstractScopedArrayBase
{
    /**
     * @psalm-var TScopedArray
     */
    private array $array;


    /**
     * @psalm-param TScopedArray $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @psalm-return TScopedArray
     */
    public function &getScopedArrayRef(): array
    {
        return $this->array;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     */
    public function keyExists(ScopeSelectorInterface $scope, string $key): bool
    {
    }

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     * @psalm-return TUnscopedArray[TKey]
     */
    public function itemGet(array $scope, string $key): mixed
    {
    }

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     * @psalm-param TUnscopedArray[TKey] $value
     */
    public function itemSet(array $scope, string $key, mixed $value): void
    {
    }

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     */
    public function itemUnset(array $scope, mixed $key): void
    {
    }

    /**
     * @psalm-param array<string> $scope
     */
    protected static function getNestedKeyString(array $scope): string
    {
        return '['.implode('][', array_map(fn (string $k) => var_export($k, true), $scope)).']';
    }
}

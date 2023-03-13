<?php

namespace Tailors\Lib\Injector;


/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TUnscopedArray of array<string,mixed>
 * @psalm-type TScopedArray array {
 *      ClassScope?: array<string, TUnscopedArray>,
 *      NamespaceScope?: array<string, TUnscopedArray>,
 *      FunctionScope?: array<string, TUnscopedArray>,
 *      MethodScope?: array<string, array<string, TUnscopedArray>>,
 *      GlobalScope?: TUnscopedArray
 * }
 *
 * @psalm-type TScopeKey ScopeSelectorInterface<TScopedArray>
 */
interface ScopedArrayInterface
{
    /**
     * @psalm-return TScopedArray
     */
    public function &getScopedArrayRef(): array;

    /**
     * @psalm-template TKey of string
     * @psalm-param TScopeKey $scopeKey
     * @psalm-param TKey $key
     */
    public function keyExists(ScopeSelectorInterface $scope, string $key): bool;

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     * @psalm-return TUnscopedArray[TKey]
     */
    public function itemGet(ScopeSelectorInterface $scope, string $key): mixed;

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     * @psalm-param TUnscopedArray[TKey] $value
     */
    public function itemSet(ScopeSelectorInterface $scope, string $key, mixed $value): void;

    /**
     * @psalm-template TKey of key-of<TUnscopedArray>
     * @psalm-param TScopeKey $scope
     * @psalm-param TKey $key
     */
    public function itemUnset(ScopeSelectorInterface $scope, mixed $key): void;
}

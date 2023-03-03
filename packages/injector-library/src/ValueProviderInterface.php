<?php

namespace Tailors\Lib\Injector;

/**
 * Provides a value depending on context.
 *
 * @psalm-template TValue
 *
 * @internal This interface is not covered by backward compatibility promise
 * @psalm-internal Tailors\Lib\Injector
 */
interface ValueProviderInterface
{
    /**
     * @psalm-return ?TValue
     */
    public function getGlobalValue(): mixed;

    public function hasScopedValues(string $scopeType): bool;

    /**
     * @psalm-return array<string,TValue>
     */
    public function getScopedValues(string $scopeType): array;

    /**
     * @psalm-return TValue
     */
    public function getScopedValue(string $scopeType, string $scopeName): mixed;

    public function hasScopedValue(string $scopeType, string $scopeName): bool;
}

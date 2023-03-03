<?php

namespace Tailors\Lib\Injector;

/**
 * @psalm-template TValue
 *
 * @internal This interface is not covered by backward compatibility promise
 * @psalm-internal Tailors\Lib\Injector
 */
interface ValueMutatorInterface
{
    /**
     * @psalm-param TValue $value
     */
    public function setGlobalValue(mixed $value): void;

    public function unsetGlobalValue(): void;

    /**
     * @psalm-param TValue $value
     */
    public function setScopedValue(string $scopeType, string $scopeName, mixed $value): void;

    public function unsetScopedValue(string $scopeType, string $scopeName): void;

    public function unsetScopedValues(string $scopeType): void;
}

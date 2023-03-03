<?php

namespace Tailors\Lib\Injector;

/**
 * @psalm-template TValue
 * @template-implements ValueProviderInterface<TValue>
 * @template-implements ValueMutatorInterface<TValue>
 *
 * @internal This class is not covered by backward compatibility promise
 * @psalm-internal Tailors\Lib\Injector
 */
final class ValueProvider implements ValueProviderInterface, ValueMutatorInterface
{
    /**
     * @psalm-var ?TValue
     */
    private mixed $globalValue;

    /**
     * @psalm-var array<string,array<string,TValue>>
     */
    private array $scopedValues;


    /**
     * @psalm-param ?TValue $globalValue
     * @psalm-param array<string,array<string,TValue>> $scopedValues
     */
    public function __construct(mixed $globalValue = null, array $scopedValues = null)
    {
        $this->globalValue = $globalValue;
        $this->scopedValues = $scopedValues ?? [];
    }


    /**
     * @psalm-return ?TValue
     */
    public function getGlobalValue(): mixed
    {
        return $this->globalValue;
    }

    public function hasScopedValues(string $scopeType): bool
    {
        return isset($this->scopedValues[$scopeType]);
    }

    /**
     * @psalm-return array<string,TValue>
     * @throws \RuntimeException
     */
    public function getScopedValues(string $scopeType): array
    {
        if (!isset($this->scopedValues[$scopeType])) {
            throw new \RuntimeException('Die!');
        }
        return $this->scopedValues[$scopeType];
    }

    /**
     * @psalm-return TValue
     * @throws \RuntimeException
     */
    public function getScopedValue(string $scopeType, string $scopeName): mixed
    {
        $scopedValues = $this->getScopedValues($scopeType);
        return $scopedValues[$scopeName];
    }

    /**
     * @psalm-assert-if-true array<string,TValue> $this->scopedValues[$scopeType]
     */
    public function hasScopedValue(string $scopeType, string $scopeName): bool
    {
        return self::arrayHasNestedKey($this->scopedValues, $scopeType, $scopeName);
    }

    /**
     * @psalm-param TValue $value
     */
    public function setGlobalValue(mixed $value): void
    {
        $this->globalValue = $value;
    }

    public function unsetGlobalValue(): void
    {
        $this->globalValue = null;
    }

    /**
     * @psalm-param TValue $value
     */
    public function setScopedValue(string $scopeType, string $scopeName, mixed $value): void
    {
        $this->scopedValues[$scopeType][$scopeName] = $value;
    }

    public function unsetScopedValue(string $scopeType, string $scopeName): void
    {
        if (isset($this->scopedValues[$scopeType])) {
            unset($this->scopedValues[$scopeType][$scopeName]);
            if (empty($this->scopedValues[$scopeType])) {
                unset($this->scopedValues[$scopeType]);
            }
        }
    }

    public function unsetScopedValues(string $scopeType): void
    {
        unset($this->scopedValues[$scopeType]);
    }

    /**
     * @psalm-pure
     *
     * @psalm-template TKey of array-key
     * @psalm-template TNestedKey of array-key
     * @psalm-template TNestedValue
     *
     * @psalm-param array<TKey,array<TNestedKey, TNestedValue>> $array
     * @psalm-param TKey $key
     * @psalm-param TNestedKey $subkey
     *
     * @psalm-assert-if-true array<TNestedKey,TNestedValue> $array[$key]
     * @psalm-assert-if-true TNestedValue $array[$key][$subkey]
     */
    private static function arrayHasNestedKey(array $array, mixed $key, mixed $subkey): bool
    {
        if (null === ($nestedArray = $array[$key] ?? null)) {
            return false;
        }
        return array_key_exists($subkey, $nestedArray);
    }
}

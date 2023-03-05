<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TValue
 *
 * @template-implements ValueProviderInterface<TValue>
 * @template-implements ValueMutatorInterface<TValue>
 *
 * @internal This class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ValueProvider implements ValueProviderInterface, ValueMutatorInterface
{
    /**
     * @psalm-var array<string,array<string,TValue>>
     */
    private array $values;

    /**
     * @psalm-param array<string,array<string,TValue>> $values
     */
    public function __construct(array $values = null)
    {
        $this->values = $values ?? [];
    }

    /**
     * Returns a list of scope types for which scoped values are defined.
     *
     * @psalm-return list<string>
     */
    public function getScopeTypes(): array
    {
        return array_keys($this->values);
    }

    /**
     * Returns true if there are any scoped values for the given *$scopeType*.
     */
    public function hasScopeType(string $scopeType): bool
    {
        return isset($this->values[$scopeType]);
    }

    /**
     * Returns a list of defined scopes of given type.
     *
     * @throws UndefinedScopeTypeException
     *
     * @psalm-return list<string>
     */
    public function getScopeNames(string $scopeType): array
    {
        if (!isset($this->values[$scopeType])) {
            throw new UndefinedScopeTypeException(sprintf(
                'Value not defined for any scope of type %s',
                var_export($scopeType, true)
            ));
        }

        return array_keys($this->values[$scopeType]);
    }

    /**
     * @throws UndefinedScopeTypeException
     *
     * @psalm-return array<string,TValue>
     */
    public function getValues(string $scopeType): array
    {
        if (!isset($this->values[$scopeType])) {
            throw new UndefinedScopeTypeException(sprintf(
                'Value not defined for any scope of type %s',
                var_export($scopeType, true)
            ));
        }

        return $this->values[$scopeType];
    }

    /**
     * @throws UndefinedScopeTypeException
     * @throws UndefinedScopeException
     *
     * @psalm-return TValue
     */
    public function getValue(string $scopeType = '', string $scopeName = ''): mixed
    {
        $values = $this->getValues($scopeType);
        if (!array_key_exists($scopeName, $values)) {
            throw new UndefinedScopeException(sprintf(
                'Value not defined for scope %s of type %s',
                var_export($scopeName, true),
                var_export($scopeType, true)
            ));
        }

        return $values[$scopeName];
    }

    /**
     * @psalm-assert-if-true array<string,TValue> $this->values[$scopeType]
     */
    public function hasValue(string $scopeType = '', string $scopeName = ''): bool
    {
        if (null === ($values = $this->values[$scopeType] ?? null)) {
            return false;
        }

        return array_key_exists($scopeName, $values);
    }

    /**
     * @psalm-param TValue $value
     */
    public function setValue(mixed $value, string $scopeType = '', string $scopeName = ''): void
    {
        $this->values[$scopeType][$scopeName] = $value;
    }

    public function unsetValue(string $scopeType = '', string $scopeName = ''): void
    {
        if (isset($this->values[$scopeType])) {
            unset($this->values[$scopeType][$scopeName]);
            if (empty($this->values[$scopeType])) {
                unset($this->values[$scopeType]);
            }
        }
    }

    /**
     * Unset all values for the given scope type.
     */
    public function unsetValues(string $scopeType): void
    {
        unset($this->values[$scopeType]);
    }

    /**
     * Lookup for a value in the order specified by *$scopes*.
     *
     * @throws UndefinedValueException
     *
     * @psalm-param array<list{string,string|array<string>}> $scopes
     *
     * @psalm-return TValue
     */
    public function lookup(array $scopes): mixed
    {
        foreach ($scopes as $scope) {
            $type = $scope[0];
            if (isset($this->values[$type]) && self::arrayLookup($this->values[$type], (array) $scope[1], $result)) {
                return $result;
            }
        }

        $summary = self::lookupSummary($scopes);

        throw new UndefinedValueException(sprintf('Value not defined for any of the scopes: %s', $summary));
    }

    /**
     * @psalm-template K of array-key
     * @psalm-template T
     *
     * @psalm-param array<K, T> $array
     * @psalm-param array<K> $keys
     *
     * @psalm-param-out ?T $result
     *
     * @psalm-assert-if-true T $result
     */
    public static function arrayLookup(array $array, array $keys, mixed &$result = null): bool
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result = $array[$key];

                return true;
            }
        }

        return false;
    }

    /**
     * @psalm-param array<list{string,string|array<string>}> $scopes
     */
    private static function lookupSummary(array $scopes): string
    {
        $strings = array_map(function (array $scope) {
            return var_export($scope[0], true).': ['.
                implode(', ', array_map(fn (string $name) => var_export($name, true), (array) $scope[1])).
            ']';
        }, $scopes);

        return implode(', ', $strings);
    }
}

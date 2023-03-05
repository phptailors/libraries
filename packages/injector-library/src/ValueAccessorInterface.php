<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * Provides a value that may depend on context. Different values may be
 * returned for different "scopes". Scopes are identified by names (strings)
 * under scope types (strings).
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TValue
 *
 * @internal This interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ValueAccessorInterface
{
    /**
     * Returns a list of defined scope types.
     *
     * @psalm-return list<string>
     */
    public function getScopeTypes(): array;

    /**
     * Returns true if there are any scoped values for the given *$scopeType*.
     */
    public function hasScopeType(string $scopeType): bool;

    /**
     * Returns a list of defined scopes of given type.
     *
     * @psalm-return list<string>
     */
    public function getScopeNames(string $scopeType): array;

    /**
     * @psalm-return array<string,TValue>
     */
    public function getValues(string $scopeType): array;

    /**
     * Returns true if a scoped value is defined.
     */
    public function hasValue(string $scopeType = '', string $scopeName = ''): bool;

    /**
     * Returns scoped value.
     *
     * @psalm-return TValue
     */
    public function getValue(string $scopeType = '', string $scopeName = ''): mixed;
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TValue
 *
 * @internal This interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ValueMutatorInterface
{
    /**
     * @psalm-param TValue $value
     */
    public function setValue(mixed $value, string $scopeType = '', string $scopeName = ''): void;

    /**
     * Unset scoped value.
     */
    public function unsetValue(string $scopeType = '', string $scopeName = ''): void;

    /**
     * Unset all values for the given scope type.
     */
    public function unsetValues(string $scopeType): void;
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TFunctionScopeLookup = string
 *
 * @template-extends ScopeLookupInterface<TFunctionScopeLookup>
 */
interface FunctionScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TFunctionScopeLookup
     */
    public function getScopeLookup(): string;

    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{ClassScope?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

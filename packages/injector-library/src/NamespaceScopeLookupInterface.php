<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TNamespaceScopeLookup = string|list<string>
 *
 * @template-extends ScopeLookupInterface<TNamespaceScopeLookup>
 */
interface NamespaceScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TNamespaceScopeLookup
     */
    public function getScopeLookup(): string|array;

    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{NamespaceScope?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

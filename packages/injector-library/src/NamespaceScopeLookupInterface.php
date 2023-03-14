<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @template-extends TwoLevelScopeLookupInterface<'namespace'>
 */
interface NamespaceScopeLookupInterface extends TwoLevelScopeLookupInterface
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{namespace?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

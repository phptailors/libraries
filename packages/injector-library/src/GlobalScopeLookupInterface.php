<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @template-extends OneLevelScopeLookupInterface<'global'>
 */
interface GlobalScopeLookupInterface extends OneLevelScopeLookupInterface
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{global?: TUnscopedArray, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

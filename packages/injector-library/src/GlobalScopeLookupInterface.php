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
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{global?: array<TKey,TVal>, ...} $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

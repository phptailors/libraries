<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TClassScopeLookup = string|list<string>
 *
 * @template-extends ScopeLookupInterface<string|list<string>>
 */
interface ClassScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TClassScopeLookup
     */
    public function getScopeLookup(): string|array;

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

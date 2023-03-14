<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @template-extends ThreeLevelScopeLookupInterface<'method'>
 */
interface MethodScopeLookupInterface extends ThreeLevelScopeLookupInterface
{
    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{method?: array<string,array<string,TUnscopedArray>>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool;
}

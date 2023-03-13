<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TNamespaceScopeLookup from NamespaceScopeLookupInterface
 */
final class NamespaceScopeLookup implements NamespaceScopeLookupInterface
{
    /**
     * @template-use TwoLevelLookupTrait<TNamespaceScopeLookup>
     */
    use TwoLevelLookupTrait;

    /**
     * @psalm-var TNamespaceScopeLookup
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param TNamespaceScopeLookup $scopeLookup
     */
    public function __construct(string|array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::NamespaceScope;
    }

    /**
     * @psalm-return TNamespaceScopeLookup
     */
    public function getScopeLookup(): string|array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{NamespaceScope?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        if (!isset($array['NamespaceScope'])) {
            return false;
        }
        return $this->twoLevelLookup($array['NamespaceScope'], $key, $retval);
    }
}

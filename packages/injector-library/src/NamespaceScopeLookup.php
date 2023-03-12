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
}

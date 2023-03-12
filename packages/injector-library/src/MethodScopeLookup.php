<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TMethodScopeLookup from MethodScopeLookupInterface
 */
final class MethodScopeLookup implements MethodScopeLookupInterface
{
    /**
     * @psalm-var TMethodScopeLookup
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param TMethodScopeLookup $scopeLookup
     */
    public function __construct(array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::MethodScope;
    }

    /**
     * @psalm-return TMethodScopeLookup
     */
    public function getScopeLookup(): array
    {
        return $this->scopeLookup;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TFunctionScopeLookup from FunctionScopeLookupInterface
 */
final class FunctionScopeLookup implements FunctionScopeLookupInterface
{
    /**
     * @psalm-var TFunctionScopeLookup
     */
    private string $scopeLookup;

    /**
     * @psalm-param TFunctionScopeLookup $scopeLookup
     */
    public function __construct(string $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::FunctionScope;
    }

    /**
     * @psalm-return TFunctionScopeLookup
     */
    public function getScopeLookup(): string
    {
        return $this->scopeLookup;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TClassScopeLookup from ClassScopeLookupInterface
 */
final class ClassScopeLookup implements ClassScopeLookupInterface
{
    /**
     * @psalm-var TClassScopeLookup
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param TClassScopeLookup $scopeLookup
     */
    public function __construct(string|array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::ClassScope;
    }

    /**
     * @psalm-return TClassScopeLookup
     */
    public function getScopeLookup(): string|array
    {
        return $this->scopeLookup;
    }
}

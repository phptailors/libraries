<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TGlobalScopeLookup from GlobalScopeLookupInterface
 */
final class GlobalScopeLookup implements GlobalScopeLookupInterface
{
    public function getScopeType(): ScopeType
    {
        return ScopeType::GlobalScope;
    }

    /**
     * @psalm-return TGlobalScopeLookup
     */
    public function getScopeLookup(): mixed
    {
        return null;
    }
}

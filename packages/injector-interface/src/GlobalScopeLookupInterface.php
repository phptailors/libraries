<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TGlobalScopeLookup = null
 *
 * @template-extends ScopeLookupInterface<TGlobalScopeLookup>
 */
interface GlobalScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TGlobalScopeLookup
     */
    public function getScopeLookup(): null;
}

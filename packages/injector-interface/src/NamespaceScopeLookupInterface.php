<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TNamespaceScopeLookup = string|list<string>
 *
 * @template-extends ScopeLookupInterface<TNamespaceScopeLookup>
 */
interface NamespaceScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TNamespaceScopeLookup
     */
    public function getScopeLookup(): string|array;
}

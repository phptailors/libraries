<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeLookup of null|string|list
 */
interface ScopeLookupInterface
{
    public function getScopeType(): ScopeType;

    /**
     * @psalm-return TScopeLookup
     */
    public function getScopeLookup(): null|string|array;
}

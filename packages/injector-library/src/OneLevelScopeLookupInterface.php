<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeType of string
 *
 * @template-extends ScopeLookupInterface<TScopeType>
 */
interface OneLevelScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return null
     */
    public function getScopeLookup(): mixed;
}

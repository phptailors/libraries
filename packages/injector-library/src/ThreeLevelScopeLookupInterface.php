<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeType of string
 *
 * @template-extends ScopeLookupInterface<TScopeType>
 */
interface ThreeLevelScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return list{string,string|array<string>}
     */
    public function getScopeLookup(): array;
}

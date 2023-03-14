<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeType of string
 *
 * @template-extends ScopeLookupInterface<TScopeType>
 */
interface TwoLevelScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return string|array<string>
     */
    public function getScopeLookup(): string|array;
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TScopeType of string
 */
interface ScopeLookupInterface
{
    /**
     * @return TScopeType
     */
    public function getScopeType(): string;

    /**
     * @psalm-return null|string|array<string>|list{string,string|array<string>}
     */
    public function getScopeLookup(): mixed;
}
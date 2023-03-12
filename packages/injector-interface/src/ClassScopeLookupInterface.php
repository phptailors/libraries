<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TClassScopeLookup = string|list<string>
 *
 * @template-extends ScopeLookupInterface<string|list<string>>
 */
interface ClassScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TClassScopeLookup
     */
    public function getScopeLookup(): string|array;
}

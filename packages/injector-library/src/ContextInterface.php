<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TLookupScopes list<
 *      MethodScopeLookupInterface |
 *      ClassScopeLookupInterface |
 *      FunctionScopeLookupInterface |
 *      NamespaceScopeLookupInterface |
 *      GlobalScopeLookupInterface
 *  >
 */
interface ContextInterface
{
    /**
     * @psalm-return TLookupScopes
     */
    public function getLookupScopes(): array;
}

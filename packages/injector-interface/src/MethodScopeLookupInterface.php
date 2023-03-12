<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TClassScopeLookup from ClassScopeLookupInterface
 *
 * @psalm-type TMethodScopeLookup = list{string,TClassScopeLookup}
 *
 * @template-extends ScopeLookupInterface<TMethodScopeLookup>
 */
interface MethodScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TMethodScopeLookup
     */
    public function getScopeLookup(): array;
}

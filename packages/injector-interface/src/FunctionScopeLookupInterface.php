<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TFunctionScopeLookup = string
 *
 * @template-extends ScopeLookupInterface<TFunctionScopeLookup>
 */
interface FunctionScopeLookupInterface extends ScopeLookupInterface
{
    /**
     * @psalm-return TFunctionScopeLookup
     */
    public function getScopeLookup(): string;
}

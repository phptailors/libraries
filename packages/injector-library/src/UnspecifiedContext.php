<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<string>
 *
 * @psalm-import-type TLookupArray from ContextInterface
 */
final class UnspecifiedContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-return TLookupArray
     */
    public function makeLookupArray(): array
    {
        return [];
    }
}

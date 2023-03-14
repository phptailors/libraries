<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class FunctionScopeLookup extends AbstractTwoLevelScopeLookupBase implements FunctionScopeLookupInterface
{
    /**
     * @psalm-return 'function'
     */
    public function getScopeType(): string
    {
        return 'function';
    }
}

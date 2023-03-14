<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class MethodScopeLookup extends AbstractThreeLevelScopeLookupBase implements MethodScopeLookupInterface
{
    /**
     * @psalm-return 'method'
     */
    public function getScopeType(): string
    {
        return 'method';
    }
}

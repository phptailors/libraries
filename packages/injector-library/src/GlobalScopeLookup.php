<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class GlobalScopeLookup extends AbstractOneLevelScopeLookupBase implements GlobalScopeLookupInterface
{
    /**
     * @psalm-return 'global'
     */
    public function getScopeType(): string
    {
        return 'global';
    }
}

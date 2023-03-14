<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class NamespaceScopeLookup extends AbstractTwoLevelScopeLookupBase implements NamespaceScopeLookupInterface
{
    public function getScopeType(): string
    {
        return 'namespace';
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class ClassScopeLookup extends AbstractTwoLevelScopeLookupBase implements ClassScopeLookupInterface
{
    /**
     * @psalm-return 'class'
     */
    public function getScopeType(): string
    {
        return 'class';
    }
}

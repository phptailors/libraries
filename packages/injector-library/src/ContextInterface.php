<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-type TLookupArray array<array-key|array>
 */
interface ContextInterface
{
    /**
     * @psalm-return TLookupArray
     */
    public function getLookupArray(): array;
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template TValue
 */
interface FactoryInterface
{
    /**
     * @psalm-return TValue
     */
    public function create(ResolverInterface $resolver): mixed;

    /**
     * Returns true if the created instance shall be shared (is a singleton).
     */
    public function shared(): bool;
}

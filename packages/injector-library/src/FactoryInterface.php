<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template ReturnType
 */
interface FactoryInterface
{
    /**
     * @psalm-return ReturnType
     */
    public function create(ResolverInterface $resolver): mixed;
}

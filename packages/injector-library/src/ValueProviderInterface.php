<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal This interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-template TValue
 *
 * @template-extends ValueAccessorInterface<TValue>
 * @template-extends ValueMutatorInterface<TValue>
 */
interface ValueProviderInterface extends ValueAccessorInterface, ValueMutatorInterface
{
    /**
     * Lookup for a value in the order specified by *$scopes*.
     *
     * @psalm-param array<list{string,string|array<string>}> $scopes
     */
    public function lookup(array $scopes): mixed;
}

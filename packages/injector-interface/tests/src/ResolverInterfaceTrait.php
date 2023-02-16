<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait ResolverInterfaceTrait
{
    public mixed $resolve;
    public mixed $resolveObject;

    public function resolve(string $abstract): mixed
    {
        return $this->resolve;
    }

    /**
     * @psalm-suppress ImplementedReturnTypeMismatch
     * @psalm-suppress MixedInferredReturnType
     */
    public function resolveObject(string $type): object
    {
        return $this->resolveObject;
    }
}

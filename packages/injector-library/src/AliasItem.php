<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class AliasItem implements ItemInterface
{
    /**
     * @psalm-readonly
     */
    private string $target;

    public function __construct(string $target)
    {
        $this->target = $target;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function resolve(ResolverInterface $resolver): mixed
    {
        return $resolver->resolve($this->target);
    }
}

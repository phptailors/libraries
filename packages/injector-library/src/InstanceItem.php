<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
final class InstanceItem implements ItemInterface
{
    /**
     * @psalm-readonly
     */
    private mixed $instance;

    public function __construct(mixed $instance)
    {
        $this->instance = $instance;
    }

    public function getInstance(): mixed
    {
        return $this->instance;
    }

    public function resolve(ResolverInterface $resolver): mixed
    {
        return $this->instance;
    }
}

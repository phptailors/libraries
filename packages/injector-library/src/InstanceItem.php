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
    private object $instance;

    public function __construct(object $instance)
    {
        $this->instance = $instance;
    }

    public function getInstance(): object
    {
        return $this->instance;
    }

    public function resolve(ResolverInterface $resolver): object
    {
        return $this->instance;
    }
}

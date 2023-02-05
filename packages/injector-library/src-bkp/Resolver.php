<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * Implementation of the ResolverInterface.
 */
final class Resolver implements ResolverInterface
{
    private InjectorContainerInterface $container;

    public function __construct(InjectorContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer(): InjectorContainerInterface
    {
        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(string $abstract, array $parameters = null): mixed
    {
        // TODO:
        return null;
    }

    private function resolveAlias(string $abstract): string
    {
    }
}

<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\Lib\Singleton\SingletonTrait;

/**
 * A composite context factory which collects other factories and organizes
 * them into a stack.
 *
 * All factories from the stack get requested by getContextManger() to create a
 * context manager, starting from the top and walking down. The first factory,
 * which returns non-null, wins.
 */
final class ContextFactoryStack implements ContextFactoryStackInterface, ContextFactoryInterface, SingletonInterface
{
    use SingletonTrait;

    /**
     * @var ContextFactoryInterface[]
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private array $factories;

    /**
     * {@inheritdoc}
     */
    public function clean(): void
    {
        $this->factories = [];
    }

    /**
     * {@inheritdoc}
     */
    public function top(): ?ContextFactoryInterface
    {
        return array_slice($this->factories, -1)[0] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function push(ContextFactoryInterface $factory): void
    {
        array_push($this->factories, $factory);
    }

    /**
     * Pops and returns the factory from the top of stack shortening the array
     * of factories by one element.
     *
     * If the stack is empty, returns null
     */
    public function pop(): ?ContextFactoryInterface
    {
        return array_pop($this->factories);
    }

    /**
     * {@inheritdoc}
     */
    public function size(): int
    {
        return count($this->factories);
    }

    /**
     * {@inheritdoc}
     */
    public function getContextManager($arg): ?ContextManagerInterface
    {
        for ($i = count($this->factories) - 1; $i >= 0; --$i) {
            $factory = $this->factories[$i];
            if (null !== ($cm = $factory->getContextManager($arg))) {
                return $cm;
            }
        }

        return null;
    }

    /**
     * Initializes the object.
     */
    private function initializeSingleton(): void
    {
        $this->clean();
    }
}

// vim: syntax=php sw=4 ts=4 et:

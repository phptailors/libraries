<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ContextAwareInterface
{
    public function setContext(ContextInterface $context): void;
    public function getContext(): ContextInterface;
}

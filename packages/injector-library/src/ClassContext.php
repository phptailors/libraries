<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<class-string>
 *
 * @psalm-import-type TLookupArray from ContextInterface
 */
final class ClassContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-return TLookupArray
     */
    protected function makeLookupArray(): array
    {
        $namespace = ContextHelper::getNamespaceOf($this->name());
        $classes = ContextHelper::getClassLookupArray($this->name());
        $lookup = [['class', $classes]];

        return $this->appendNamespaceAndGlobalLookups($namespace, $lookup);
    }
}

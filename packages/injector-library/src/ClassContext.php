<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<class-string>
 *
 * @psalm-import-type TLookupScopes from ContextInterface
 */
final class ClassContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-return TLookupScopes
     */
    protected function makeLookupScopes(): array
    {
        $namespace = ContextHelper::getNamespaceOf($this->name());

        return $this->appendNamespaceAndGlobalLookupScopes($namespace, [
            new ClassScopeLookup(ContextHelper::getClassScopeLookup($this->name())),
        ]);
    }
}

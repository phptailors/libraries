<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<string>
 *
 * @psalm-import-type TLookupScopes from ContextInterface
 */
final class FunctionContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-return TLookupScopes
     */
    public function makeLookupScopes(): array
    {
        $namespace = ContextHelper::getNamespaceOf($this->name());

        return $this->appendNamespaceAndGlobalLookupScopes($namespace, [
            new FunctionScopeLookup($this->name()),
        ]);
    }
}

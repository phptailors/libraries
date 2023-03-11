<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<class-string>
 */
final class ClassContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-var ?array<list{string,string|array<string>}>
     */
    private ?array $scopes = null;

    /**
     * @psalm-return array<list{string,string|array<string>}>
     */
    public function getLookupScopes(): array
    {
        if (!isset($this->scopes)) {
            $this->scopes = [
                [ScopeType::ClassScope->value, ContextHelper::getClassLookupScopes($this->name())],
            ];
            if (!empty($namespaceScopes = array_slice(ContextHelper::getNamespaceLookupScopes($this->name()), 1))) {
                $this->scopes[] = [ScopeType::NamespaceScope->value, $namespaceScopes];
            }
            $this->scopes[] = [ScopeType::GlobalScope->value, ''];
        }

        return $this->scopes;
    }
}

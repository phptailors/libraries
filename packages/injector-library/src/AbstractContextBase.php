<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TName of string
 *
 * @psalm-import-type TLookupScopes from ContextInterface
 */
abstract class AbstractContextBase
{
    /**
     * @psalm-var ?TLookupScopes
     */
    private ?array $scopes = null;

    /**
     * @psalm-var TName
     *
     * @psalm-readonly
     */
    private string $name;

    /**
     * @psalm-param TName $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @psalm-return TName
     */
    final public function name(): string
    {
        return $this->name;
    }

    /**
     * @psalm-return TLookupScopes
     */
    final public function getLookupScopes(): array
    {
        if (!isset($this->scopes)) {
            $this->scopes = $this->makeLookupScopes();
        }

        return $this->scopes;
    }

    /**
     * @psalm-return TLookupScopes
     */
    abstract protected function makeLookupScopes(): array;

    /**
     * @psalm-param TLookupScopes $scopes
     *
     * @psalm-return TLookupScopes
     */
    final protected function appendNamespaceAndGlobalLookupScopes(string $namespace, array $scopes): array
    {
        if (!empty($nsScopes = ContextHelper::getNamespaceScopeLookup($namespace))) {
            $scopes[] = new NamespaceScopeLookup($nsScopes);
        }
        $scopes[] = new GlobalScopeLookup();

        return $scopes;
    }
}

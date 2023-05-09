<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TName of string
 *
 * @psalm-import-type TLookupArray from ContextInterface
 */
abstract class AbstractContextBase
{
    /**
     * @psalm-var ?TLookupArray
     */
    private ?array $lookupArray = null;

    /**
     * @psalm-var TName
     *
     * @psalm-readonly
     */
    private readonly string $name;

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
     * @psalm-return TLookupArray
     */
    final public function getLookupArray(): array
    {
        if (!isset($this->lookupArray)) {
            $this->lookupArray = $this->makeLookupArray();
        }

        return $this->lookupArray;
    }

    /**
     * @psalm-return TLookupArray
     */
    abstract protected function makeLookupArray(): array;

    /**
     * @psalm-param TLookupArray $lookup
     *
     * @psalm-return TLookupArray
     */
    final protected function appendNamespaceAndGlobalLookups(string $namespace, array $lookup): array
    {
        if (!empty($namespaces = ContextHelper::getNamespaceLookupArray($namespace))) {
            $lookup[] = ['namespace', $namespaces];
        }
        $lookup[] = ['global'];

        return $lookup;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TClassScopeLookup from ClassScopeLookupInterface
 */
final class ClassScopeLookup implements ClassScopeLookupInterface
{
    /**
     * @template-use TwoLevelLookupTrait<TClassScopeLookup>
     */
    use TwoLevelLookupTrait;

    /**
     * @psalm-var TClassScopeLookup
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param TClassScopeLookup $scopeLookup
     */
    public function __construct(string|array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::ClassScope;
    }

    /**
     * @psalm-return TClassScopeLookup
     */
    public function getScopeLookup(): string|array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array{ClassScope?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        if (!isset($array['ClassScope'])) {
            return false;
        }
        return $this->twoLevelLookup($array['ClassScope'], $key, $retval);
    }
}

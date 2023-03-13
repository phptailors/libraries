<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-import-type TFunctionScopeLookup from FunctionScopeLookupInterface
 */
final class FunctionScopeLookup implements FunctionScopeLookupInterface
{
    /**
     * @template-use TwoLevelLookup<TFunctionScopeLookup>
     */
    use TwoLevelLookup;

    /**
     * @psalm-var TFunctionScopeLookup
     */
    private string $scopeLookup;

    /**
     * @psalm-param TFunctionScopeLookup $scopeLookup
     */
    public function __construct(string $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    public function getScopeType(): ScopeType
    {
        return ScopeType::FunctionScope;
    }

    /**
     * @psalm-return TFunctionScopeLookup
     */
    public function getScopeLookup(): string
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array{FunctionScope?: array<string,TUnscopedArray>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        if (!isset($array['FunctionScope'])) {
            return false;
        }
        return $this->twoLevelLookup($array['FunctionScope'], $key, $retval);
    }
}

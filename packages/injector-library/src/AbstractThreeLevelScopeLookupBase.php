<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractThreeLevelScopeLookupBase
{
    use OneLevelLookupTrait;
    use TwoLevelLookupTrait;
    use ThreeLevelLookupTrait;

    /**
     * @psalm-var list{string,string|array<string>}
     */
    private array $scopeLookup;

    /**
     * @psalm-param list{string,string|array<string>} $scopeLookup
     */
    final public function __construct(array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    /**
     * @psalm-return 'method'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return list{string,string|array<string>}
     */
    final public function getScopeLookup(): array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TKey of string
     * @psalm-template TUnscopedArray of array<string,mixed>
     *
     * @psalm-param array{method?: array<string,array<string,TUnscopedArray>>, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    final public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        return self::threeLevelLookup($this->scopeLookup, $array[$this->getScopeType()] ?? null, $key, $retval);
    }
}

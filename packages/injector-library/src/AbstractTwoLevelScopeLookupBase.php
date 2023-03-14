<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractTwoLevelScopeLookupBase
{
    use OneLevelLookupTrait;
    use TwoLevelLookupTrait;

    /**
     * @psalm-var string|array<string>
     */
    private string|array $scopeLookup;

    /**
     * @psalm-param string|array<string> $scopeLookup
     */
    final public function __construct(string|array $scopeLookup)
    {
        $this->scopeLookup = $scopeLookup;
    }

    /**
     * @psalm-return 'class'|'function'|'namespace'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return string|array<string>
     */
    final public function getScopeLookup(): string|array
    {
        return $this->scopeLookup;
    }

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array{
     *      class?: array<string,TUnscopedArray>,
     *      namespace?: array<string,TUnscopedArray>,
     *      function?: array<string,TUnscopedArray>,
     *      ...
     * } $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    final public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        /** @psalm-suppress PossiblyUndefinedArrayOffset */ // psalm bug...
        return self::twoLevelLookup((array) $this->scopeLookup, $array[$this->getScopeType()] ?? null, $key, $retval);
    }
}

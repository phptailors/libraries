<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
abstract class AbstractOneLevelScopeLookupBase
{
    use OneLevelLookupTrait;

    /**
     * @psalm-return 'global'
     */
    abstract public function getScopeType(): string;

    /**
     * @psalm-return null
     */
    final public function getScopeLookup(): mixed
    {
        return null;
    }

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array{global?: TUnscopedArray, ...} $array
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    final public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        return self::oneLevelLookup($array[$this->getScopeType()] ?? null, $key, $retval);
    }
}

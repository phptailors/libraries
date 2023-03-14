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
     * @psalm-template TKey of string
     * @psalm-template TVal of mixed
     *
     * @psalm-param array{global?: array<TKey,TVal>, ...} $array
     *
     * @psalm-param-out null|TVal $retval
     *
     * @psalm-assert-if-true TVal $retval
     */
    final public function lookup(array $array, string $key, mixed &$retval = null): bool
    {
        return self::oneLevelLookup($array[$this->getScopeType()] ?? null, $key, $retval);
    }
}

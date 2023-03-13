<?php

namespace Tailors\Lib\Injector;

/**
 * @psalm-template TScopeLookup of string|list<string>
 */
trait TwoLevelLookupTrait
{
    /**
     * @psalm-return TScopeLookup
     */
    abstract public function getScopeLookup(): string|array;

    /**
     * @psalm-template TUnscopedArray of array<string,mixed>
     * @psalm-template TKey of string
     *
     * @psalm-param array<string,TUnscopedArray> $scopes
     * @psalm-param TKey $key
     *
     * @psalm-param-out null|TUnscopedArray[TKey] $retval
     */
    private function twoLevelLookup(array $scopes, string $key, mixed &$retval = null): bool
    {
        $skeys = (array) $this->getScopeLookup();
        foreach ($skeys as $skey) {
            if (isset($scopes[$skey])) {
                $scope = $scopes[$skey];
                if (array_key_exists($key, $scope)) {
                    /** @psalm-var TUnscopedArray[TKey] */
                    $retval = $scope[$key];

                    return true;
                }
            }
        }

        return false;
    }
}

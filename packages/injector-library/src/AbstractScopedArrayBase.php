<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @psalm-template TUnscopedArray of array<string,mixed>
 */
abstract class AbstractScopedArrayBase
{
    /**
     * @psalm-var array{
     *      class?: array<string, TUnscopedArray>,
     *      namespace?: array<string, TUnscopedArray>,
     *      function?: array<string, TUnscopedArray>,
     *      method?: array<string, array<string, TUnscopedArray>>,
     *      global?: TUnscopedArray
     * }
     */
    private array $array;

    /**
     * @psalm-param array{
     *      class?: array<string, TUnscopedArray>,
     *      namespace?: array<string, TUnscopedArray>,
     *      function?: array<string, TUnscopedArray>,
     *      method?: array<string, array<string, TUnscopedArray>>,
     *      global?: TUnscopedArray
     * } $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @psalm-return array{
     *      class?: array<string, TUnscopedArray>,
     *      namespace?: array<string, TUnscopedArray>,
     *      function?: array<string, TUnscopedArray>,
     *      method?: array<string, array<string, TUnscopedArray>>,
     *      global?: TUnscopedArray
     * }
     */
    public function getScopedArray(): array
    {
        return $this->array;
    }

//    /**
//     * @psalm-template TKey of string
//     * @psalm-param TScopeKey $scope
//     * @psalm-param TKey $key
//     */
//    public function keyExists(ScopeSelectorInterface $scope, string $key): bool
//    {
//    }
//
//    /**
//     * @psalm-template TKey of key-of<TUnscopedArray>
//     * @psalm-param TScopeKey $scope
//     * @psalm-param TKey $key
//     * @psalm-return TUnscopedArray[TKey]
//     */
//    public function itemGet(array $scope, string $key): mixed
//    {
//    }
//
//    /**
//     * @psalm-template TKey of key-of<TUnscopedArray>
//     * @psalm-param TScopeKey $scope
//     * @psalm-param TKey $key
//     * @psalm-param TUnscopedArray[TKey] $value
//     */
//    public function itemSet(array $scope, string $key, mixed $value): void
//    {
//    }
//
//    /**
//     * @psalm-template TKey of key-of<TUnscopedArray>
//     * @psalm-param TScopeKey $scope
//     * @psalm-param TKey $key
//     */
//    public function itemUnset(array $scope, mixed $key): void
//    {
//    }
//
//    /**
//     * @psalm-param array<string> $scope
//     */
//    protected static function getNestedKeyString(array $scope): string
//    {
//        return '['.implode('][', array_map(fn (string $k) => var_export($k, true), $scope)).']';
//    }
}

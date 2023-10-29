<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use Tailors\Lib\Collections\NestedArray;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TScopeType "class"|"function"|"global"|"method"|"namespace"
 * @psalm-type TScopePath list{0: TScopeType, 1?:string, 2?: string}
 * @psalm-type TItemPath list{0: TScopeType, 1: string,  2?: string, 3?: string}
 */
final class ContextualArray
{
    /**
     * @psalm-var array{
     *      class: 3,
     *      function: 3,
     *      method: 4,
     *      namesace: 3,
     *      global: 2
     * }
     *
     * @psalm-readonly
     */
    private static array $pathLength = [
        'class'     => 3,
        'function'  => 3,
        'method'    => 4,
        'namespace' => 3,
        'global'    => 2,
    ];

    /**
     * @psalm-param non-empty-list<string> $path
     */
    public static function get(array $array, array $path, mixed &$retval = null): bool
    {
        $type = $path[0];

        if (count($path) !== (self::$pathLength[$type] ?? null)) {
            return false;
        }

        return NestedArray::get($array, $path, $retval);
    }

    /**
     * @psalm-param TItemPath $path
     */
    public static function set(array &$array, array $path, mixed $value): void
    {
        $type = $path[0];

        if (count($path) !== (self::$pathLength[$type] ?? null)) {
            return;
        }

        NestedArray::set($array, $path, $value);
    }

    /**
     * @psalm-param non-empty-list<string> $path
     */
    public static function del(array &$array, array $path): void
    {
        $type = $scope[0];

        if (count($path) !== (self::$pathLength[$type] ?? null)) {
            return;
        }

        NestedArray::del($array, $path);
    }

    /**
     * @psalm-param non-empty-array<array-key|array> $lookup
     *
     * @psalm-return ?TItemPath
     */
    public static function lookup(array $array, array $lookup, mixed &$retval = null): ?array
    {
        /** @psalm-var ?non-empty-list<string> */
        $path = NestedArray::lookup($array, $lookup, $temp);
        if (null === $path) {
            return null;
        }

        $type = $path[0];
        if (count($path) !== (self::$pathLength[$type] ?? null)) {
            return null;
        }

        /** @psalm-var mixed */
        $retval = $temp;

        return $path;
    }
}

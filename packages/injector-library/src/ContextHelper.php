<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal This class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContextHelper
{
    public static function getNamespaceOf(string $fqdn): string
    {
        $pieces = array_filter(explode('\\', $fqdn));

        return implode('\\', array_slice($pieces, 0, -1));
    }

    /**
     * @psalm-return list<string>
     */
    public static function getNamespaceLookupArray(string $namespace): array
    {
        $pieces = array_filter(explode('\\', $namespace));
        $lookup = [];
        while (!empty($pieces)) {
            $lookup[] = implode('\\', $pieces);
            array_pop($pieces);
        }

        return $lookup;
    }

    /**
     * @psalm-param class-string|trait-string $class
     *
     * @psalm-return list<class-string>
     */
    public static function getClassLookupArray(string $class): array
    {
        $lookup = [$class];
        if (!empty($parents = class_parents($class))) {
            $lookup = array_merge($lookup, array_values($parents));
        }
        if (!empty($ifaces = class_implements($class))) {
            $ifaces = array_values($ifaces);
            sort($ifaces);
            $lookup = array_merge($lookup, $ifaces);
        }

        return $lookup;
    }
}

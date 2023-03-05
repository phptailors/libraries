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
    /**
     * @psalm-return list<string>
     */
    public static function getNamespaceLookupScopes(string $namespace): array
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
    public static function getClassLookupScopes(string $class): array
    {
        $scopes = [$class];
        if (!empty($parents = class_parents($class))) {
            $scopes = array_merge($scopes, array_values($parents));
        }
        if (!empty($ifaces = class_implements($class))) {
            $scopes = array_merge($scopes, array_values($ifaces));
        }

        return $scopes;
    }
}

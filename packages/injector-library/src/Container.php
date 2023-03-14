<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @psalm-type TAliases array{
 *      class?:     array<string,array<string,string>>,
 *      namespace?: array<string,array<string,string>>,
 *      function?:  array<string,array<string,string>>,
 *      method?:    array<string,array<string, array<string,string>>>,
 *      global?:    array<string,string>
 * }
 *
 * @psalm-type TInstances array{
 *      class?:     array<string,class-string-map<T,T>>,
 *      namespace?: array<string,class-string-map<T,T>>,
 *      function?:  array<string,class-string-map<T,T>>,
 *      method?:    array<string,array<string, class-string-map<T,T>>>,
 *      global?:    class-string-map<T,T>
 * }
 */
final class Container
{
    /**
     * @psalm-var TAliases
     */
    private array $aliases;

    /**
     * @psalm-param TAliases $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }

    public function lookupAlias(string $key, ContextInterface $context): ?string
    {
        foreach ($context->getLookupScopes() as $lookup) {
            if ($lookup->lookupScopedArray($this->aliases, $key, $retval)) {
                return $retval;
            }
        }

        return null;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

final class Container
{
    /**
     * @psalm-var array{
     *      class?:     array<string,array<string,string>>,
     *      namespace?: array<string,array<string,string>>,
     *      function?:  array<string,array<string,string>>,
     *      method?:    array<string,array<string, array<string,string>>>,
     *      global?:    array<string,string>
     * }
     */
    private array $aliases;

    /**
     * @psalm-param array{
     *      class?:     array<string,array<string,string>>,
     *      namespace?: array<string,array<string,string>>,
     *      function?:  array<string,array<string,string>>,
     *      method?:    array<string,array<string, array<string,string>>>,
     *      global?:    array<string,string>
     * } $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }

    public function lookupAlias(string $key, ContextInterface $context): ?string
    {
        foreach ($context->getLookupScopes() as $lookup) {
            if ($lookup->lookup($this->aliases, $key, $retval)) {
                return $retval;
            }
        }

        return null;
    }
}

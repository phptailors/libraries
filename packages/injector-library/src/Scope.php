<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TOptions = array{
 *  aliases?: array<string,string>
 * }
 */
final class Scope implements ScopeInterface
{
    use ScopeAliasesTrait;

    /**
     * @psalm-param TOptions $options
     *
     * @throws CircularDependencyException
     */
    public function __construct(array $options = [])
    {
        $aliases = $options['aliases'] ?? [];
        self::aliasAssertNoCycles($aliases);
        $this->aliases = $aliases;
    }
}

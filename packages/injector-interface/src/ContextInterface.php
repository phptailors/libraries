<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
interface ContextInterface
{
    public function type(): ContextType;

    public function name(): string;

    /**
     * @psalm-return array<list{string,string|array<string>}>
     */
    public function getLookupScopes(): array;
}

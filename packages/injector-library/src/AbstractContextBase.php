<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @psalm-template TName of string
 */
abstract class AbstractContextBase
{
    /**
     * @psalm-readonly
     */
    private ContextType $type;

    /**
     * @psalm-var TName
     *
     * @psalm-readonly
     */
    private string $name;

    /**
     * @psalm-param TName $name
     */
    public function __construct(ContextType $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    final public function type(): ContextType
    {
        return $this->type;
    }

    /**
     * @psalm-return TName
     */
    final public function name(): string
    {
        return $this->name;
    }
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik
 *
 * @template-extends AbstractContextBase<string>
 *
 * @psalm-import-type TLookupArray from ContextInterface
 */
final class MethodContext extends AbstractContextBase implements ContextInterface
{
    /**
     * @psalm-var class-string|trait-string
     */
    private string $className;

    /**
     * @psalm-param class-string|trait-string $className
     */
    public function __construct(string $name, string $className)
    {
        $this->className = $className;
        parent::__construct($name);
    }

    public function className(): string
    {
        return $this->className;
    }

    /**
     * @psalm-return TLookupArray
     */
    protected function makeLookupArray(): array
    {
        $namespace = ContextHelper::getNamespaceOf($this->className);
        $classes = ContextHelper::getClassLookupArray($this->className);

        $lookup = [
            ['method', [$this->name(), $classes]],
            ['class', $classes],
        ];

        return $this->appendNamespaceAndGlobalLookups($namespace, $lookup);
    }
}

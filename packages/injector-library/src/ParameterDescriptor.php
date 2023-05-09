<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TOptions array{
 *      class?: class-string,
 *      type?: non-empty-string
 * }
 */
final class ParameterDescriptor implements ParameterDescriptorInterface
{
    /**
     * @psalm-readonly
     *
     * @psalm-var non-negative-int
     */
    private readonly int $position;

    /**
     * @psalm-readonly
     *
     * @psalm-var non-empty-string
     */
    private readonly string $name;

    /**
     * @psalm-readonly
     *
     * @psalm-var non-empty-string
     */
    private readonly string $function;

    /**
     * @psalm-readonly
     *
     * @psalm-var ?class-string
     */
    private readonly ?string $class;

    /**
     * @psalm-readonly
     *
     * @psalm-var ?non-empty-string
     */
    private readonly ?string $type;

    /**
     * @psalm-param non-negative-int $position
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $function
     * @psalm-param TOptions $options
     */
    public function __construct(int $position, string $name, string $function, array $options = [])
    {
        $this->position = $position;
        $this->name = $name;
        $this->function = $function;
        $this->class = $options['class'] ?? null;
        $this->type = $options['type'] ?? null;
    }

    public static function fromReflection(\ReflectionParameter $param): self
    {
        $function = $param->getDeclaringFunction();

        /** @psalm-var non-negative-int */
        $position = $param->getPosition();

        /** @psalm-var TOptions */
        $options = [];

        if ($param->hasType()) {
            /** @psalm-var non-empty-string */
            $options['type'] = (string) $param->getType();
        }

        if (null !== ($class = $param->getDeclaringClass())) {
            $options['class'] = $class->getName();
        }

        return new self($position, $param->getName(), $function->getName(), $options);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the function name of the function declaring the parameter.
     *
     * @psalm-return non-empty-string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * Returns the class name of the method declaring the parameter, if this is a method parameter, or null otherwise.
     *
     * @psalm-return ?class-string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Returns the declared parameter type, or null if parameter type was not declared.
     *
     * @psalm-return ?non-empty-string
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}

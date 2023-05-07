<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ParameterDescriptorInterface
{
    /**
     * @psalm-return non-negative-int
     */
    public function getPosition(): int;

    /**
     * @psalm-return non-empty-string
     */
    public function getName(): string;

    /**
     * Returns the function name of the function declaring the parameter.
     *
     * @psalm-return non-empty-string
     */
    public function getFunction(): string;

    /**
     * Returns the class name of the method declaring the parameter, if this is a method parameter, or null otherwise.
     *
     * @psalm-return ?class-string
     */
    public function getClass(): ?string;

    /**
     * Returns the declared parameter type, or null if parameter type was not declared.
     *
     * @psalm-return ?non-empty-string
     */
    public function getType(): ?string;
}

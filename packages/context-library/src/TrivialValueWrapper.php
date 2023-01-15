<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * A trivial context manager which only wraps a single value.
 *
 * The enterContext() method returns the ``$value`` passed as argument to
 * ``__construct()``, while exitContext() returns false.
 *
 * @template ValueType of mixed
 *
 * @psalm-immutable
 */
final class TrivialValueWrapper implements ContextManagerInterface
{
    /**
     * @var mixed
     *
     * @psalm-var ValueType
     */
    private mixed $value;

    /**
     * Initializes the object.
     *
     * @param mixed $value the value being wrapped by the object
     *
     * @psalm-param ValueType $value
     */
    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * Returns the value provided to constructor.
     *
     * @psalm-return ValueType
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return ValueType
     */
    public function enterContext(): mixed
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function exitContext(\Throwable $exception = null): bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:

<?php
declare(strict_types=1);

namespace Tailors\Lib\Context;

/**
 * Executes user code within a predefined context.
 */
final class WithContextExecutor implements ExecutorInterface
{
    /**
     * @var ContextManagerInterface[]
     *
     * @psalm-readonly
     */
    private array $context;

    /**
     * Initializes the object.
     *
     * @param ContextManagerInterface[] $context
     */
    public function __construct(array $context)
    {
        $this->context = $context;
    }

    /**
     * Calls user function within context.
     *
     * @param callable $func The user function to be called
     *
     * @return mixed the value returned by ``$func``
     *
     * @throws \Throwable rethrows exception thrown from ``$func``
     *
     * @psalm-template ReturnType
     *
     * @psalm-param callable(...):ReturnType $func
     *
     * @psalm-return ReturnType
     */
    public function __invoke(callable $func): mixed
    {
        $exception = null;
        $return = null;

        $i = 0;

        try {
            $args = $this->enterContext($i);

            $return = call_user_func_array($func, $args);
        } catch (\Throwable $e) {
            $exception = $e;
        }

        // exit all the entered contexts
        $exception = $this->exitContext($i, $exception);

        if (null !== $exception) {
            throw $exception;
        }

        return $return;
    }

    /**
     * Returns the context provided to __construct().
     *
     * @psalm-mutation-free
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Invokes ``enterContext()`` method on the context managers from
     * ``$this->context`` array.
     *
     * @param int $i
     *               Index used by the internal loop (passed by reference, so
     *               its value is not lost when an exception is thrown)
     *
     * @return array
     *               An array of arguments to be passed to user function
     */
    private function enterContext(int &$i): array
    {
        $args = [];
        for (; $i < count($this->context); ++$i) {
            /** @psalm-var mixed */
            $args[] = $this->context[$i]->enterContext();
        }

        return $args;
    }

    /**
     * Invokes ``exitContext()`` method on the context managers from
     * ``$this->context`` array.
     *
     * @param int        $i
     *                              Index used by the internal loop (passed by reference, so its
     *                              value is not lost when an exception is thrown)
     * @param \Throwable $exception
     *                              An exception thrown from enterContext() or from user's
     *                              callback
     *
     * @return null|\Throwable
     *                         An exception or null (if the exception was handled by one of
     *                         the context managers)
     */
    private function exitContext(int &$i, \Throwable $exception = null): ?\Throwable
    {
        for ($i--; $i >= 0; --$i) {
            if ($this->context[$i]->exitContext($exception)) {
                $exception = null;
            }
        }

        return $exception;
    }
}

// vim: syntax=php sw=4 ts=4 et:

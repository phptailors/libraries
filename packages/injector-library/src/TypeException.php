<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

final class TypeException extends \TypeError implements TypeExceptionInterface
{
    public static function fromTypeAndValue(
        string $expected,
        mixed $actual,
        int $code = 0,
        ?\Throwable $previous = null
    ): self {
        $type = get_debug_type($actual);
        $message = sprintf('%s(): Return value must be of type %s, %s returned', self::caller(1), $expected, $type);

        return new self($message, $code, $previous);
    }

    /**
     * @psalm-param positive-int $depth
     */
    private static function caller(int $depth): string
    {
        ++$depth;
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 + $depth);
        if ($depth >= count($backtrace)) {
            return '<unknown>';
        }
        $call = $backtrace[$depth];
        if (!isset($call['class'])) {
            return $call['function'];
        }

        return sprintf('%s::%s', $call['class'], $call['function']);
    }
}

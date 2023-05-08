<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

final class CircularDependencyException extends \Exception implements CircularDependencyExceptionInterface
{
    /**
     * @psalm-param array<string> $trace
     */
    public static function fromBacktrace(
        array $trace,
        string $repeating,
        int $code = 0,
        ?\Throwable $previous = null
    ): self {
        return new self(self::messageFromBacktrace($trace, $repeating), $code, $previous);
    }

    /**
     * @psalm-param array<string> $trace
     */
    private static function messageFromBacktrace(array $trace, string $repeating): string
    {
        $circle = self::circle([...$trace, $repeating], $repeating);

        if (count($circle) < 2) {
            return 'circular dependency';
        }

        $string = implode(' -> ', array_map(fn (string $s): string => var_export($s, true), $circle));

        return sprintf('circular dependency: %s', $string);
    }

    /**
     * @psalm-param array<string> $trace
     *
     * @psalm-return list<string>
     */
    private static function circle(array $trace, string $delimiter): array
    {
        $list = array_values($trace);

        if (false === ($start = array_search($delimiter, $list, true))) {
            return [];
        }

        $tail = array_slice($list, 1 + $start);

        if (false === ($end = array_search($delimiter, $tail, true))) {
            return $tail;
        }

        return [$delimiter, ...array_slice($tail, 0, $end), $delimiter];
    }
}

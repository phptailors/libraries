<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

final class CircularDependencyException extends \Exception implements CircularDependencyExceptionInterface
{
    /**
     * @psalm-param array<string> $seen
     */
    public static function fromSeenAndFound(
        array $seen,
        string $found,
        int $code = 0,
        ?\Throwable $previous = null
    ): self {
        return new self(self::messageFromSeenAndFound($seen, $found), $code, $previous);
    }

    /**
     * @psalm-param array<string> $seen
     */
    private static function messageFromSeenAndFound(array $seen, string $found): string
    {
        $circle = self::circle([...$seen, $found], $found);

        if (count($circle) < 2) {
            return 'circular dependency';
        }

        $string = implode(' -> ', array_map(fn (string $s): string => var_export($s, true), $circle));

        return sprintf('circular dependency: %s', $string);
    }

    /**
     * @psalm-param array<string> $path
     *
     * @psalm-return list<string>
     */
    private static function circle(array $path, string $found): array
    {
        $circle = [];
        foreach ($path as $p) {
            if (empty($circle)) {
                if ($p === $found) {
                    $circle[] = $p;
                }
            } else {
                $circle[] = $p;
                if ($p === $found) {
                    break;
                }
            }
        }

        return $circle;
    }
}

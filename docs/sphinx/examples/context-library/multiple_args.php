<?php
/* [code] */
/* [use] */
use Tailors\Lib\Context\ContextManagerInterface;
use function Tailors\Lib\Context\with;
/* [/use] */

/* [MyInt] */
final class MyInt implements ContextManagerInterface
{
    public int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function enterContext(): int
    {
        echo "enter: " . $this->value . "\n";
        return $this->value;
    }

    public function exitContext(?\Throwable $exception = null) : bool
    {
        echo "exit: " . $this->value . "\n";
        return false;
    }
}
/* [/MyInt] */

/* [test] */
with(new MyInt(1), new MyInt(2), new MyInt(3))(function (int ...$args) {
    echo '$args: ' . implode(', ', $args) . "\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

<?php
/* [code] */
/* [use] */
use Tailors\Lib\Context\ContextManagerInterface;
use function Tailors\Lib\Context\with;
/* [/use] */

/* [MyValueWrapper] */
final class MyValueWrapper implements ContextManagerInterface
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function enterContext(): string
    {
        echo "MyValueWrapper::enter()\n";
        return $this->value;
    }

    public function exitContext(?\Throwable $exception = null) : bool
    {
        echo "MyValueWrapper::exit()\n";
        return false; // we didn't handle $exception
    }
}
/* [/MyValueWrapper] */

/* [test] */
with(new MyValueWrapper('argument value'))(function (string $value) {
    echo $value . "\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

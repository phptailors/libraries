<?php
/* [code] */
/* [use] */
use function Tailors\Lib\Context\with;
use Tailors\Lib\Context\AbstractManagedContextFactory;
use Tailors\Lib\Context\ContextManagerInterface;
/* [/use] */

/* [MyCounter] */
final class MyCounter
{
    public $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
/* [/MyCounter] */

/* [MyCounterManager] */
final class MyCounterManager implements ContextManagerInterface
{
    public $counter;

    public function __construct(MyCounter $counter)
    {
        $this->counter = $counter;
    }

    public function enterContext()
    {
        $this->counter->value ++;
        print("MyCounterManager::enterContext()\n");
        return $this->counter;
    }

    public function exitContext(?\Throwable $exception = null) : bool
    {
        $this->counter->value --;
        print("MyCounterManager::exitContext()\n");
        return false;
    }
}
/* [/MyCounterManager] */

/* [MyContextFactory] */
final class MyContextFactory extends AbstractManagedContextFactory
{
    public function getContextManager($arg) : ?ContextManagerInterface
    {
        if($arg instanceof MyCounter) {
            return new MyCounterManager($arg);
        }
        return null;
    }
}
/* [/MyContextFactory] */

/* [test] */
with(new MyContextFactory(), new MyCounter(0))(function ($cf, $cnt) {
    echo "before: " . $cnt->value . "\n";
    with($cnt)(function ($cnt) {
        echo "in: " . $cnt->value . "\n";
    });
    echo "after: " . $cnt->value . "\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

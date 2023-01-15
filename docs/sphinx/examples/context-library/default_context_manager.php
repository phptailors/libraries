<?php
/* [code] */
/* [use] */
use Tailors\Lib\Context\TrivialValueWrapper;
use function Tailors\Lib\Context\with;
/* [/use] */

/* [test] */
with('argument value')(function (string $value) {
    echo $value . "\n";
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

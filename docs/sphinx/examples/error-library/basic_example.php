<?php
/* [code] */
/* [use] */
use function Tailors\Lib\Context\with;
use function Tailors\Lib\Error\empty_error_handler;
/* [/use] */

/* [trouble] */
function trouble()
{
    trigger_error('you have a problem');
}
/* [/trouble] */

/* [test] */
with(empty_error_handler())(function ($eh) {
    trouble();
});
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

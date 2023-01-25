<?php
/* [code] */
/* [use] */
use function Tailors\Lib\Context\with;
use function Tailors\Lib\Error\caller_error_handler;
/* [/use] */

/* [handler] */
function handler(int $severity, string $message, string $file, int $line) : bool
{
    printf("error occured at %s: %d: (%s)\n", basename($file), $line, $message);
    return true;
}
/* [/handler] */

/* [trigger] */
function trigger()
{
    with(caller_error_handler(handler::class))(function ($eh) {
        printf("trigger_error() called at: %s: %d\n", basename(__file__), __line__ + 1);
        @trigger_error("error message");
    });
}
/* [/trigger] */

/* [test] */
printf("trigger() called at: %s: %d\n", basename(__file__), __line__ + 1);
trigger();
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

<?php
/* [code] */
/* [use] */
use function Tailors\Lib\Context\with;
use function Tailors\Lib\Error\errorHandler;
/* [/use] */

/* [watch] */
function watch(int $severity, string $message, string $file, int $line) : bool
{
    if ($severity & (E_USER_WARNING|E_USER_ERROR)) {
        fprintf(STDERR, "beware, %s!\n", $message);
    } else {
        fprintf(STDOUT, "be cool, %s\n", $message);
    }
    return true;
}
/* [/watch] */

/* [test] */
with(errorHandler(watch::class))(function ($eh) {
    @trigger_error('the weather is nice', E_USER_NOTICE);
    @trigger_error('rain is coming', E_USER_WARNING);
});
@trigger_error('good night', E_USER_NOTICE);
/* [/test] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

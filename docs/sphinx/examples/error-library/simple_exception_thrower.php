<?php
/* [code] */
/* [use] */
use function Tailors\Lib\Context\with;
use function Tailors\Lib\Error\exception_error_handler;
/* [/use] */

/* [try-catch] */
try {
    with(exception_error_handler(\ErrorException::class))(function ($eh) {
        @trigger_error('boom!', E_USER_ERROR);
    });
} catch (\ErrorException $e) {
    fprintf(STDERR, "%s:%d:ErrorException:%s\n", basename($e->getFile()), $e->getLine(), $e->getMessage());
    exit(1);
}
/* [/try-catch] */
/* [/code] */

// vim: syntax=php sw=4 ts=4 et:

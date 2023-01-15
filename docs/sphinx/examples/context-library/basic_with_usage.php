<?php

use function Tailors\Lib\Context\with;

with(fopen(__DIR__.'/hello.txt', 'r'))(function ($fd) {
  echo stream_get_contents($fd)."\n";
});

// vim: syntax=php sw=4 ts=4 et:

<?php

use Tailors\Lib\Singleton\SingletonInterface;
use Tailors\Lib\Singleton\SingletonTrait;

final class TrivialSingleton implements SingletonInterface
{
    use SingletonTrait;
}

$object = TrivialSingleton::getInstance();

// vim: syntax=php sw=4 ts=4 et:

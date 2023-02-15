<?php declare(strict_types=1);

namespace Tailors\Lib\Singleton;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
trait SingletonInterfaceTrait
{
    public static mixed $instance;

    public static function getInstance(): mixed
    {
        return self::$instance;
    }
}

// vim: syntax=php sw=4 ts=4 et:

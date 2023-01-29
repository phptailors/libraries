<?php declare(strict_types=1);

namespace Tailors\Lib\Singleton;

/**
 * An interface implemented by singleton classes.
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
interface SingletonInterface
{
    /**
     * Fetch an instance of the class.
     */
    public static function getInstance(): mixed;
}

// vim: syntax=php sw=4 ts=4 et:

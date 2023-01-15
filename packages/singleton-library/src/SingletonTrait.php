<?php

declare(strict_types=1);

namespace Tailors\Lib\Singleton;

/**
 * A trait for singleton classes.
 *
 * @psalm-consistent-constructor
 */
trait SingletonTrait
{
    /**
     * Store the singleton object.
     *
     * @psalm-var ?self
     */
    private static ?object $instance;

    /**
     * Singleton's constructor is hidden from user.
     */
    private function __construct()
    {
        $this->initializeSingleton();
    }

    /**
     * Singleton's __clone() is hidden from user.
     *
     * @codeCoverageIgnore
     */
    private function __clone()
    {
    }

    /**
     * Singleton's __wakeup() always throws {@link Tailors\Lib\Singleton\SingletonException}.
     *
     * @codeCoverageIgnore
     *
     * @throws SingletonException always throws SingletonException
     */
    public function __wakeup()
    {
        throw new SingletonException(sprintf('Cannot unserialize singleton %s', self::class));
    }

    /**
     * Returns the single instance of the class.
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initializes the object.
     *
     * This method may be overwriten in the target class.
     */
    private function initializeSingleton(): void
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:

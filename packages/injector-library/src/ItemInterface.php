<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * An abstraction of items held in container.
 *
 * Basically, our container holds *aliases*, *instances*, and factory/singleton
 * *callbacks*. The `ItemInterface` provides a common abstraction of the above
 * item types. It's implemented by [AliasItem](AliasItem.html) (for aliases),
 * [InstanceItem](InstanceItem.html) (for instances), and
 * [CallbackItem](CallbackItem.html) (for callbacks). The particular
 * implementations of ItemInterface provide behaviors specific to particular
 * item types (for example resolving). See alsoe
 * [ItemContainerInterface](ItemContainerInterface.html).
 *
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @internal this interface is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
interface ItemInterface extends ResolvableInterface
{
}

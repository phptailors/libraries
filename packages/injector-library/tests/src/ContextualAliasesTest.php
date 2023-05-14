<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ContextualAliases
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 */
final class ContextualAliasesTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContextualAliasesInterface(): void
    {
        $this->assertImplementsInterface(ContextualAliasesInterface::class, ContextualAliases::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsItemContextualAliasesInterface(): void
    {
        $this->assertImplementsInterface(ItemContextualAliasesInterface::class, ContextualAliases::class);
    }
}

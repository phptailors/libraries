<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractContextBase
 * @covers \Tailors\Lib\Injector\GlobalScopeLookup
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TGlobalScopeLookup from GlobalScopeLookupInterface
 */
final class GlobalScopeLookupTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsGlobalScopeLookupInterface(): void
    {
        $this->assertImplementsInterface(GlobalScopeLookupInterface::class, GlobalScopeLookup::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeType(): void
    {
        $this->assertSame(ScopeType::GlobalScope, (new GlobalScopeLookup())->getScopeType());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopeLookup(): void
    {
        $lookup = new GlobalScopeLookup();
        $this->assertNull($lookup->getScopeLookup());
    }
}

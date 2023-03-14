<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AbstractScopedArrayBase
 * @covers \Tailors\Lib\Injector\ScopedInstanceMap
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-import-type TUnscopedArray from ScopedInstanceMap
 * @psalm-import-type TScopedArray from ScopedInstanceMap
 */
final class ScopedInstanceMapTest extends TestCase
{
    use ExtendsClassTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractScopedArrayBase(): void
    {
        $this->assertExtendsClass(AbstractScopedArrayBase::class, ScopedInstanceMap::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsScopedArrayInterface(): void
    {
        $this->assertImplementsInterface(ScopedArrayInterface::class, ScopedInstanceMap::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetScopedArray(): void
    {
        /** @psalm-var TScopedArray */
        $array = ['global' => [self::class => $this]];
        $map = new ScopedInstanceMap($array);
        $this->assertSame($array, $map->getScopedArray());
    }
}

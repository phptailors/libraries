<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\KsortedArrayIdenticalToTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Aliases
 *
 * @internal
 */
final class AliasesTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasesArray(): void
    {
        $aliases = new Aliases(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $aliases->aliasesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $aliases = new Aliases();
        $this->assertFalse($aliases->aliasExists('foo'));
        $this->assertNull($aliases->aliasSet('foo', 'bar'));
        $this->assertTrue($aliases->aliasExists('foo'));
        $this->assertFalse($aliases->aliasExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGet(): void
    {
        $aliases = new Aliases(['foo' => 'bar']);
        $this->assertSame('bar', $aliases->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGetThrowsNotFoundException(): void
    {
        $aliases = new Aliases();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('alias \'foo\' not found');

        $this->assertNull($aliases->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSet(): void
    {
        $aliases = new Aliases(['foo' => 'bar']);
        $this->assertNull($aliases->aliasSet('bar', 'baz'));
        $this->assertKsortedArrayIdenticalTo(['foo' => 'bar', 'bar' => 'baz'], $aliases->aliasesArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $aliases = new Aliases(['foo' => 'bar']);
        $this->assertNull($aliases->aliasUnset('foo'));
        $this->assertSame([], $aliases->aliasesArray());
        // Unsetting inexistent alias is harmless
        $this->assertNull($aliases->aliasUnset('inexisteng'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSetThrowsCyclicAliasException(): void
    {
        $aliases = new Aliases(['foo' => 'bar', 'bar' => 'baz']);

        $this->expectException(CyclicAliasException::class);
        $this->expectExceptionMessage('cyclic alias detected: \'baz\' -> \'foo\' -> \'bar\' -> \'baz\'');

        $this->assertNull($aliases->aliasSet('baz', 'foo'));
    }

    /**
     * @psalm-return iterable<array-key, list{0:array<string,string>,1:string,2:string}>
     */
    public static function provAliasResolve(): iterable
    {
        yield [['foo' => 'bar'], 'foo', 'bar'];

        yield [['foo' => 'bar', 'baz' => 'foo'], 'foo', 'bar'];

        yield [['foo' => 'bar', 'baz' => 'foo', 'bar' => 'gez'], 'foo', 'gez'];
    }

    /**
     * @dataProvider provAliasResolve
     *
     * @psalm-suppress MissingThrowsDocblock
     *
     * @psalm-param array<string, string> $aliases
     */
    public function testAliasResolve(array $aliases, string $alias, string $expect): void
    {
        $aliases = new Aliases($aliases);
        $this->assertSame($expect, $aliases->aliasResolve($alias));
    }
}

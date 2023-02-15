<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\KsortedArrayIdenticalToTrait;

/**
 * Class using AliasesTrait for testing purposes.
 */
final class Aliases5EMA9 implements AliasesInterface
{
    use AliasesTrait;

    /**
     * @psalm-param array<string,string> $aliases
     */
    public function __construct(array $aliases = [])
    {
        $this->aliases = $aliases;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\AliasesTrait
 *
 * @internal
 */
final class AliasesTraitTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetAliases(): void
    {
        $aliases = new Aliases5EMA9(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $aliases->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $aliases = new Aliases5EMA9();
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
        $aliases = new Aliases5EMA9(['foo' => 'bar']);
        $this->assertSame('bar', $aliases->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGetThrowsNotFoundException(): void
    {
        $aliases = new Aliases5EMA9();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('alias \'foo\' not found');

        $this->assertNull($aliases->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSet(): void
    {
        $aliases = new Aliases5EMA9(['foo' => 'bar']);
        $this->assertNull($aliases->aliasSet('bar', 'baz'));
        $this->assertKsortedArrayIdenticalTo(['foo' => 'bar', 'bar' => 'baz'], $aliases->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $aliases = new Aliases5EMA9(['foo' => 'bar']);
        $this->assertNull($aliases->aliasUnset('foo'));
        $this->assertSame([], $aliases->getAliases());
        // Unsetting inexistent alias is harmless
        $this->assertNull($aliases->aliasUnset('inexisteng'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSetThrowsCyclicAliasException(): void
    {
        $aliases = new Aliases5EMA9(['foo' => 'bar', 'bar' => 'baz']);

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
        $aliases = new Aliases5EMA9($aliases);
        $this->assertSame($expect, $aliases->aliasResolve($alias));
    }
}

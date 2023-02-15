<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\KsortedArrayIdenticalToTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Scope
 * @covers \Tailors\Lib\Injector\AliasesTrait
 *
 * @internal
 */
final class ScopeTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;
    use KsortedArrayIdenticalToTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsScopeInterface(): void
    {
        $this->assertImplementsInterface(ScopeInterface::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUsesAliasesTrait(): void
    {
        $this->assertUsesTrait(AliasesTrait::class, Scope::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithoutArgs(): void
    {
        $scope = new Scope();
        $this->assertSame([], $scope->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithAliases(): void
    {
        $scope = new Scope(['aliases' => ['foo' => 'bar']]);
        $this->assertSame(['foo' => 'bar'], $scope->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructWithCyclicAliases(): void
    {
        $this->expectException(CyclicAliasException::class);
        $this->expectExceptionMessage('cyclic alias detected: \'foo\' -> \'bar\' -> \'baz\' -> \'foo\'');

        $this->assertNotNull(new Scope([
            'aliases' => [
                'foo' => 'bar',
                'bar' => 'baz',
                'gez' => 'foo',
                'baz' => 'foo',
            ],
        ]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasExists(): void
    {
        $scope = new Scope();
        $this->assertFalse($scope->aliasExists('foo'));
        $this->assertNull($scope->aliasSet('foo', 'bar'));
        $this->assertTrue($scope->aliasExists('foo'));
        $this->assertFalse($scope->aliasExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGet(): void
    {
        $scope = new Scope(['aliases' => ['foo' => 'bar']]);
        $this->assertSame('bar', $scope->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasGetThrowsNotFoundException(): void
    {
        $scope = new Scope();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('alias \'foo\' not found');

        $this->assertNull($scope->aliasGet('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSet(): void
    {
        $scope = new Scope(['aliases' => ['foo' => 'bar']]);
        $this->assertNull($scope->aliasSet('bar', 'baz'));
        $this->assertKsortedArrayIdenticalTo(['foo' => 'bar', 'bar' => 'baz'], $scope->getAliases());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasUnset(): void
    {
        $scope = new Scope(['aliases' => ['foo' => 'bar']]);
        $this->assertNull($scope->aliasUnset('foo'));
        $this->assertSame([], $scope->getAliases());
        // Unsetting inexistent alias is harmless
        $this->assertNull($scope->aliasUnset('inexisteng'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAliasSetThrowsCyclicAliasException(): void
    {
        $scope = new Scope(['aliases' => ['foo' => 'bar', 'bar' => 'baz']]);

        $this->expectException(CyclicAliasException::class);
        $this->expectExceptionMessage('cyclic alias detected: \'baz\' -> \'foo\' -> \'bar\' -> \'baz\'');

        $this->assertNull($scope->aliasSet('baz', 'foo'));
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
        $scope = new Scope(['aliases' => $aliases]);
        $this->assertSame($expect, $scope->aliasResolve($alias));
    }
}

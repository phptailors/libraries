<?php

declare(strict_types=1);

namespace Tailors\Tests\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\Lib\Injector\CircularDependencyException;
use Tailors\Lib\Injector\InstanceFactoryWrapper;
use Tailors\Lib\Injector\ResolverContainerInterface;
use Tailors\Lib\Injector\ResolverInterface;
use Tailors\Lib\Injector\ResolverTrait;

/**
 * @psalm-import-type AliasesArray from ResolverContainerInterface
 * @psalm-import-type BindingsArray from ResolverContainerInterface
 * @psalm-import-type InstancesArray from ResolverContainerInterface
 * @psalm-type OptionsArray = array{
 *      aliases?:AliasesArray,
 *      instances?:InstancesArray,
 *      bindings?:BindingsArray,
 *      contextualBindings?:ContextualBindingsArray
 * }
 */
final class ResolverL8KAD implements ResolverInterface, ResolverContainerInterface
{
    use ResolverTrait;

    /** @psalm-var AliasesArray */
    public array $aliases = [];

    /** @psalm-var InstancesArray */
    public array $instances = [];

    /** @psalm-var BindingsArray */
    public array $bindings = [];

    /** @psalm-var ContextualBindingsArray */
    public array $contextualBindings = [];

    /**
     * @psalm-param OptionsArray $options
     */
    public function __construct(array $options = [])
    {
        $this->aliases = $options['aliases'] ?? [];
        $this->instances = $options['instances'] ?? [];
        $this->bindings = $options['bindings'] ?? [];
        $this->contextualBindings = $options['contextualBindings'] ?? [];
    }

    /** @psalm-return AliasesArray */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /** @psalm-return InstancesArray */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /** @psalm-return BindingsArray */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @psalm-return ContextualBindingsArray
     */
    public function getContextualBindings(): array
    {
        return $this->contextualBindings;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\ResolverTrait
 *
 * @internal
 */
final class ResolverTraitTest extends TestCase
{
    /**
     * @psalm-return list<list{0:array{aliases?: AliasesArray},1:string,2:string}>
     */
    public static function provResolveAlias(): array
    {
        return [
            [[], 'foo', 'foo'],
            [['aliases' => ['foo' => 'bar']], 'gez', 'gez'],
            [['aliases' => ['foo' => 'bar']], 'foo', 'bar'],
            [['aliases' => ['foo' => 'bar', 'bar' => 'gez']], 'foo', 'gez'],
            [['aliases' => ['foo' => 'bar', 'bar' => 'gez', 'self' => 'self']], 'foo', 'gez'],
        ];
    }

    /**
     * @psalm-return list<list{0:array{aliases?: AliasesArray},1:string,2:string}>
     */
    public static function provResolveAliasCircularDependency(): array
    {
        return [
            [
                ['aliases' => ['foo' => 'foo']],
                'foo',
                'Error resolving alias: circular dependency \'foo\' => \'foo\'',
            ],
            [
                ['aliases' => ['foo' => 'bar', 'bar' => 'gez', 'self' => 'self']],
                'self',
                'Error resolving alias: circular dependency \'self\' => \'self\'',
            ],
            [
                ['aliases' => ['foo' => 'bar', 'bar' => 'foo']],
                'foo',
                'Error resolving alias: circular dependency \'bar\' => \'foo\'',
            ],
            [
                ['aliases' => ['foo' => 'bar', 'bar' => 'gez', 'gez' => 'gez']],
                'foo',
                'Error resolving alias: circular dependency \'gez\' => \'gez\'',
            ],
        ];
    }

    /**
     * @psalm-return list<list{0:array{aliases?: AliasesArray},1:string,2:bool}>
     */
    public static function provIsAlias(): array
    {
        return [
            [[], 'foo', false],
            [['aliases' => ['foo' => 'bar']], 'gez', false],
            [['aliases' => ['foo' => 'bar']], 'bar', false],
            [['aliases' => ['foo' => 'bar']], 'foo', true],
            [['aliases' => ['foo' => 'bar', 'bar' => 'gez']], 'foo', true],
            [['aliases' => ['foo' => 'bar', 'bar' => 'gez']], 'bar', true],
            [['aliases' => ['foo' => 'bar', 'bar' => 'gez']], 'gez', false],
        ];
    }

    /**
     * @psalm-return list<list{0:array{instances?: InstancesArray, bindings?: BindingsArray},1:string,2:bool}>
     */
    public static function provIsShared(): array
    {
        return [
            [[], 'foo', false],
            [['instances' => ['foo' => 'FOO']], 'bar', false],
            [['instances' => ['foo' => 'FOO']], 'foo', true],
            [
                [
                    'bindings' => [
                        'foo' => new InstanceFactoryWrapper(
                            /** @psalm-suppress UnusedClosureParam */
                            fn (ResolverInterface $resolver) => 'FOO',
                            false
                        )
                    ],
                ],
                'foo',
                false
            ],
            [
                [
                    'bindings' => [
                        'foo' => new InstanceFactoryWrapper(
                            /** @psalm-suppress UnusedClosureParam */
                            fn (ResolverInterface $resolver) => 'FOO',
                            true
                        )
                    ],
                ],
                'foo',
                true
            ],
            [
                [
                    'bindings' => [
                        'foo' => new InstanceFactoryWrapper(
                            /** @psalm-suppress UnusedClosureParam */
                            fn (ResolverInterface $resolver) => 'FOO1',
                            false
                        )
                    ],
                    'instances' => [
                        'foo' => 'FOO2'
                    ],
                ],
                'foo',
                true
            ],
        ];
    }

    /**
     * @dataProvider provResolveAlias
     *
     * @psalm-param array{aliases?:AliasesArray} $options
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveAlias(array $options, string $key, string $result): void
    {
        $resolver = new ResolverL8KAD($options);
        $this->assertSame($result, $resolver->resolveAlias($key));
    }

    /**
     * @dataProvider provResolveAliasCircularDependency
     *
     * @psalm-param array{aliases?:AliasesArray} $options
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolveAliasCircularDependency(array $options, string $key, string $message): void
    {
        $resolver = new ResolverL8KAD($options);

        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage($message);

        $this->assertSame($key, $resolver->resolveAlias($key));
    }

    /**
     * @dataProvider provIsAlias
     *
     * @psalm-param array{aliases?:AliasesArray} $options
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsAlias(array $options, string $key, bool $result): void
    {
        $resolver = new ResolverL8KAD($options);

        $this->assertSame($result, $resolver->isAlias($key));
    }

    /**
     * @dataProvider provIsShared
     *
     * @psalm-param array{instances?:InstancesArray, bindings?:BindingsArray} $options
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsShared(array $options, string $key, bool $result): void
    {
        $resolver = new ResolverL8KAD($options);

        $this->assertSame($result, $resolver->isShared($key));
    }
}

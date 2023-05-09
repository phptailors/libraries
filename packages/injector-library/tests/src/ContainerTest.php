<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\Container
 *
 * @internal this class is not covered by backward compatibility promise
 *
 * @psalm-internal Tailors\Lib\Injector
 *
 * @psalm-type TContents array{
 *      aliases?: array<string,string>,
 *      instances?: array<string,mixed>,
 *      bindings?: array<string,\Closure(ResolverInterface):mixed>,
 *      singletons?: array<string,\Closure(ResolverInterface):mixed>
 * }
 */
final class ContainerTest extends TestCase
{
    use ImplementsInterfaceTrait;

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsContainerInterface(): void
    {
        $this->assertImplementsInterface(ContainerInterface::class, Container::class);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsItemContainerInterface(): void
    {
        $this->assertImplementsInterface(ItemContainerInterface::class, Container::class);
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{0?: TContents, 1?: ResolverFactoryInterface},
     *      array{contents: TContents, resolverFactory?: mixed}
     * }>
     */
    public function provConstructor(): iterable
    {
        $bar = new \stdClass();

        /** @psalm-suppress UnusedClosureParam */
        $baz = fn (ResolverInterface $resolver): mixed => new \stdClass();

        /** @psalm-suppress UnusedClosureParam */
        $gez = fn (ResolverInterface $resolver): mixed => new \stdClass();

        $factory = $this->createStub(ResolverFactoryInterface::class);

        return [
            '#00' => [
                [],
                [
                    'contents' => [],
                ],
            ],
            '#01' => [
                [[]],
                [
                    'contents' => [],
                ],
            ],
            '#02' => [
                [[
                    'aliases'    => ['foo' => 'bar'],
                    'instances'  => ['bar' => $bar],
                    'bindings'   => ['baz' => $baz],
                    'singletons' => ['gez' => $gez],
                ]],
                [
                    'contents' => [
                        'aliases'    => ['foo' => 'bar'],
                        'instances'  => ['bar' => $bar],
                        'bindings'   => ['baz' => $baz],
                        'singletons' => ['gez' => $gez],
                    ],
                ],
            ],
            '#03' => [
                [[], $factory],
                [
                    'contents'        => [],
                    'resolverFactory' => $factory,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstructor
     *
     * @psalm-param list{0?: TContents, 1?: ResolverFactoryInterface} $args
     * @psalm-param array{contents: mixed, resolverFactory?: mixed} $expected
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstructor(array $args, array $expected): void
    {
        $container = new Container(...$args);

        // FIXME: test contents of the container...
        $this->assertSame($expected['contents'], $container->getContents());

        if (count($args) < 2) {
            $this->assertInstanceOf(ResolverFactory::class, $container->getResolverFactory());
        }

        if (array_key_exists('resolverFactory', $expected)) {
            $this->assertSame($expected['resolverFactory'], $container->getResolverFactory());
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGet(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)
            ->getMock()
        ;

        $resolverFactory = $this->getMockBuilder(ResolverFactoryInterface::class)
            ->getMock()
        ;

        $container = new Container([], $resolverFactory);

        $resolverFactory->expects($this->once())
            ->method('getResolver')
            ->with($container)
            ->willReturn($resolver)
        ;

        $resolver->expects($this->once())
            ->method('resolve')
            ->with('foo')
            ->willReturn('FOO')
        ;

        $this->assertSame('FOO', $container->get('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testResolve(): void
    {
        $resolver = $this->getMockBuilder(ResolverInterface::class)
            ->getMock()
        ;

        $resolverFactory = $this->getMockBuilder(ResolverFactoryInterface::class)
            ->getMock()
        ;

        $container = new Container([], $resolverFactory);

        $resolverFactory->expects($this->once())
            ->method('getResolver')
            ->with($container)
            ->willReturn($resolver)
        ;

        $resolver->expects($this->once())
            ->method('resolve')
            ->with('foo')
            ->willReturn('FOO')
        ;

        $this->assertSame('FOO', $container->resolve('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHas(): void
    {
        $container = new Container();

        $container->alias('foo', 'bar');

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetItemReturnsItem(): void
    {
        $container = new Container();

        $container->alias('foo', 'bar');

        $this->assertInstanceOf(ItemInterface::class, $container->getItem('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetItemThrowsNotFoundException(): void
    {
        $container = new Container();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('\'foo\' not found');

        $this->assertNull($container->getItem('foo'));
    }

    /**
     * @psalm-return iterable<array-key, list{list{0?: TContents}, mixed}>
     */
    public static function provUnsetItemUnsetsAllContentsWithGivenId(): iterable
    {
        $instance = new \stdClass();

        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => (new \stdClass());

        return [
            '#00' => [
                [],
                [],
            ],
            '#01' => [
                [[
                    'aliases'    => ['foo' => 'FOO'],
                    'instances'  => ['foo' => $instance],
                    'bindings'   => ['foo' => $callback],
                    'singletons' => ['foo' => $callback],
                ]],
                [
                    'aliases'    => [],
                    'instances'  => [],
                    'bindings'   => [],
                    'singletons' => [],
                ],
            ],
            '#02' => [
                [[
                    'aliases' => ['foo' => 'FOO'],
                ]],
                [
                    'aliases' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provUnsetItemUnsetsAllContentsWithGivenId
     *
     * @psalm-param list{0?:TContents} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnsetItemUnsetsAllContentsWithGivenId(array $args, mixed $expected): void
    {
        $container = new Container(...$args);

        $container->unsetItem('foo');

        $this->assertSame($expected, $container->getContents());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAlias(): void
    {
        $container = new Container([
            'instances' => ['foo' => null],
            'bindings' => ['foo' => fn(): mixed => null],
            'singletons' => ['foo' => fn(): mixed => null],
        ]);

        $this->assertNull($container->alias('foo', 'bar'));

        $fooItem = $container->getItem('foo');

        $this->assertInstanceOf(AliasItem::class, $fooItem);
        $this->assertSame('bar', $fooItem->getTarget());

        $contents = $container->getContents();

        $this->assertArrayNotHasKey('foo', $contents['instances'] ?? []);
        $this->assertArrayNotHasKey('foo', $contents['bindings'] ?? []);
        $this->assertArrayNotHAsKey('foo', $contents['singletons'] ?? []);
    }

    /**
     * @psalm-return iterable<array-key, list{
     *      list{string,mixed},
     *      mixed
     *  }>
     */
    public static function provInstance(): iterable
    {
        $object = new \stdClass();

        return [
            '#00' => [['object', $object], $object ],
            '#01' => [['bool', true], true],
            '#02' => [['bool', false], false],
            '#03' => [['int', 123], 123],
            '#04' => [['float', 1.23], 1.23],
            '#05' => [['string', 'FOO'], 'FOO'],
            '#06' => [['array', [1,2,3]], [1,2,3]],
            '#07' => [['null', null], null],
        ];
    }

    /**
     * @dataProvider provInstance
     *
     * @psalm-param list{string,mixed} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInstance(array $args, mixed $instance): void
    {
        $id = $args[0];
        $container = new Container([
            'aliases' => [$id => $id.'-target'],
            'bindings' => [$id => fn(): mixed => null],
            'singletons' => [$id => fn(): mixed => null],
        ]);

        $this->assertNull($container->instance(...$args));

        $item = $container->getItem($id);

        $this->assertInstanceOf(InstanceItem::class, $item);
        $this->assertSame($instance, $item->getInstance());

        $contents = $container->getContents();

        $this->assertArrayNotHasKey($id, $contents['aliases'] ?? []);
        $this->assertArrayNotHasKey($id, $contents['bindings'] ?? []);
        $this->assertArrayNotHAsKey($id, $contents['singletons'] ?? []);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testBind(): void
    {
        $container = new Container([
            'aliases' => ['foo' => 'bar'],
            'instances' => ['foo' => null],
            'singletons' => ['foo' => fn(): mixed => null],
        ]);

        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => new \stdClass();

        $this->assertNull($container->bind('foo', $callback));

        $fooItem = $container->getItem('foo');

        $this->assertInstanceOf(BindingItem::class, $fooItem);
        $this->assertSame($callback, $fooItem->getCallback());

        $contents = $container->getContents();

        $this->assertArrayNotHasKey('foo', $contents['aliases'] ?? []);
        $this->assertArrayNotHasKey('foo', $contents['instances'] ?? []);
        $this->assertArrayNotHAsKey('foo', $contents['singletons'] ?? []);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSingleton(): void
    {
        $container = new Container([
            'aliases' => ['foo' => 'bar'],
            'instances' => ['foo' => null],
            'bindings' => ['foo' => fn(): mixed => null],
        ]);

        /** @psalm-suppress UnusedClosureParam */
        $callback = fn (ResolverInterface $resolver): \stdClass => new \stdClass();

        $this->assertNull($container->singleton('foo', $callback));

        $contents = $container->getContents();

        $this->assertArrayNotHasKey('foo', $contents['aliases'] ?? []);
        $this->assertArrayNotHasKey('foo', $contents['instances'] ?? []);
        $this->assertArrayNotHAsKey('foo', $contents['bindings'] ?? []);

        // Unresolved singleton: it's essentially a callback (lazy construction)
        $this->assertInstanceOf(BindingItem::class, $container->getItem('foo'));

        $foo = $container->resolve('foo');
        $this->assertInstanceOf(\stdClass::class, $foo);

        // Resolved singleton: now it's a real instance
        $this->assertInstanceOf(InstanceItem::class, $container->getItem('foo'));

        $contents = $container->getContents();

        $this->assertArrayNotHasKey('foo', $contents['aliases'] ?? []);
        $this->assertArrayNotHAsKey('foo', $contents['bindings'] ?? []);
        $this->assertArrayNotHasKey('foo', $contents['singletons'] ?? []);
    }
}

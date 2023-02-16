<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use PHPUnit\Framework\TestCase;

/**
 * Class using NamespacesWrapperTrait for testing purposes.
 */
final class NamespacesWrapper3UTO1 implements NamespacesInterface, NamespacesWrapperInterface
{
    use NamespacesWrapperTrait;

    private NamespacesInterface $namespaces;

    public function __construct(NamespacesInterface $namespaces)
    {
        $this->namespaces = $namespaces;
    }

    public function getNamespaces(): NamespacesInterface
    {
        return $this->namespaces;
    }
}

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 *
 * @covers \Tailors\Lib\Injector\NamespacesWrapper3UTO1
 * @covers \Tailors\Lib\Injector\NamespacesWrapperTrait
 *
 * @internal
 */
final class NamespacesWrapperTraitTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetNamespaces(): void
    {
        $wrappee = $this->createStub(NamespacesInterface::class);

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertSame($wrappee, $wrapper->getNamespaces());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsArray(): void
    {
        $scope = $this->createStub(ScopeInterface::class);
        $wrappee = $this->getMockBuilder(NamespacesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('nsArray')
            ->willReturn(['foo' => $scope])
        ;

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertSame(['foo' => $scope], $wrapper->nsArray());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsExists(): void
    {
        $wrappee = $this->getMockBuilder(NamespacesInterface::class)->getMock();

        $wrappee->expects($this->exactly(2))
            ->method('nsExists')
            ->will(
                $this->returnValueMap([
                    ['foo', true],
                    ['bar', false],
                ])
            )
        ;

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertTrue($wrapper->nsExists('foo'));
        $this->assertFalse($wrapper->nsExists('bar'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsSet(): void
    {
        $scope = $this->createStub(ScopeInterface::class);
        $wrappee = $this->getMockBuilder(NamespacesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('nsSet')
            ->with('foo', $scope)
        ;

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertNull($wrapper->nsSet('foo', $scope));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsUnset(): void
    {
        $wrappee = $this->getMockBuilder(NamespacesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('nsUnset')
            ->with('foo')
        ;

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertNull($wrapper->nsUnset('foo'));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNsGet(): void
    {
        $scope = $this->createStub(ScopeInterface::class);
        $wrappee = $this->getMockBuilder(NamespacesInterface::class)->getMock();

        $wrappee->expects($this->once())
            ->method('nsGet')
            ->with('foo')
            ->willReturn($scope)
        ;

        $wrapper = new NamespacesWrapper3UTO1($wrappee);

        $this->assertSame($scope, $wrapper->nsGet('foo'));
    }
}

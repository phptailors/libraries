<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @psalm-type TUnscopedArray class-string-map<T,T>
 * @psalm-type TScopedArray array{
 *      ClassScope?: array<string, class-string-map<T,T>>,
 *      NamespaceScope?: array<string, class-string-map<T,T>>,
 *      FunctionScope?: array<string, class-string-map<T,T>>,
 *      MethodScope?: array<string, array<string, class-string-map<T,T>>>,
 *      GlobalScope?: class-string-map<T,T>
 * }
 *
 * @template-extends AbstractScopedArrayBase<class-string-map<T,T>>
 *
 * @template-implements ScopedArrayInterface<class-string-map<T,T>>
 */
final class ScopedInstanceMap extends AbstractScopedArrayBase implements ScopedArrayInterface
{
}

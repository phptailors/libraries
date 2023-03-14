<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @psalm-type TUnscopedArray class-string-map<T,T>
 * @psalm-type TScopedArray array{
 *      class?: array<string, class-string-map<T,T>>,
 *      namespace?: array<string, class-string-map<T,T>>,
 *      function?: array<string, class-string-map<T,T>>,
 *      method?: array<string, array<string, class-string-map<T,T>>>,
 *      global?: class-string-map<T,T>
 * }
 *
 * @template-extends AbstractScopedArrayBase<class-string-map<T,T>>
 *
 * @template-implements ScopedArrayInterface<class-string-map<T,T>>
 */
final class ScopedInstanceMap extends AbstractScopedArrayBase implements ScopedArrayInterface
{
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
enum ScopeType
{
    case ClassScope;

    case NamespaceScope;

    case FunctionScope;

    case MethodScope;

    case GlobalScope;
}

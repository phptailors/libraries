<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
enum ScopeType: string
{
    case ClassScope = 'C';

    case NamespaceScope = 'N';

    case FunctionScope = 'F';

    case MethodScope = 'M';

    case GlobalScope = '';
}

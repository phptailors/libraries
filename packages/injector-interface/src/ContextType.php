<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

/**
 * @author Paweł Tomulik <pawel@tomulik.pl>
 */
enum ContextType: string
{
    case ClassContext = 'C';

    case NamespaceContext = 'N';

    case FunctionContext = 'F';

    case MethodContext = 'M';

    case GlobalContext = '';
}

<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}

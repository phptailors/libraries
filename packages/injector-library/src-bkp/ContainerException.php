<?php declare(strict_types=1);

namespace Tailors\Lib\Injector;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
}

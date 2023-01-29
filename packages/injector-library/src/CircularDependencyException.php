<?php
declare(strict_types=1);

namespace Tailors\Lib\Injector;

class CircularDependencyException extends \Exception implements CircularDependencyExceptionInterface
{
}

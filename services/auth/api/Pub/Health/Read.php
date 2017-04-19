<?php

namespace Continuous\MicroServiceDemo\Auth\Api\Pub\Health;

use Continuous\MicroServiceDemo\Auth\Api\Main;
use Crudy\Server\Crud\ReadInterface;
use Crudy\Server\Dispatcher;
use Crudy\Server\JsonApi\View;
use Hoa\Router\Router;

class Read extends Main implements ReadInterface
{
    public function __construct(Router $router, Dispatcher $dispatcher, View $view)
    {
        parent::__construct($router, $dispatcher, $view);

        $this->disableHeaderResponsibilities();
    }

    public function read(string $resourceId)
    {
        $this->createResource('1', [
            'check'   => 'OK',
            'devMode' => defined('__DEV_MODE__'),
            'version' => ver(),
        ]);
    }

    public function readAll()
    {
        $this->notFound();
    }
}
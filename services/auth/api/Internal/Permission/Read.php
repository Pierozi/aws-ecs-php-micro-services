<?php

namespace Continuous\MicroServiceDemo\Auth\Api\Internal\Permission;

use Continuous\MicroServiceDemo\Auth\Api\Main;
use Crudy\Server\Crud\ReadInterface;
use Crudy\Server\JsonApi\Exception;

class Read extends Main implements ReadInterface
{
    public function read(string $resourceId)
    {
        $this->notFound();
    }

    public function readAll()
    {
        if (empty($_GET['token'])) {
            throw new Exception('Bad input value', 400);
        }

        $token = $_GET['token'];

        $user = $this->scan('user', 'token', $token);
        $user = $user->current();

        if (null === $user) {
            throw new Exception('User Not found', 400);
        }

        $this->createResource(null, [
            'token' => $token,
            'permission' => $user['permission'],
        ]);
    }
}
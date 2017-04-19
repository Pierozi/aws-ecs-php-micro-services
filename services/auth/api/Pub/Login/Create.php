<?php

namespace Continuous\MicroServiceDemo\Auth\Api\Pub\Login;

use Continuous\MicroServiceDemo\Auth\Api\Main;
use Crudy\Server\Crud\CreateInterface;
use Crudy\Server\JsonApi\Exception;
use Hoa\Consistency\Consistency;

class Create extends Main implements CreateInterface
{
    public function create()
    {
        $attribute = $this->view->getAttributes();

        if (empty($attribute->nickname) || empty($attribute->password) ) {
            throw new Exception('Bad input value', 400);
        }

        $user = $this->query('user', 'uuid', $attribute->nickname);

        if (null === $user) {
            throw new Exception('Auth fail', 400);
        }

        if (false === password_verify($attribute->password, $user['password'])) {
            throw new Exception('Auth fail', 400);
        }

        $token = hash('sha256', Consistency::uuid());

        $this->patch('user', 'uuid', $user['uuid'], [
            'token' => $token,
        ]);

        $this->createResource(Consistency::uuid(), [
            'user' => $user['uuid'],
            'token' => $token,
        ]);
    }
}
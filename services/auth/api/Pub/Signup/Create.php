<?php

namespace Continuous\MicroServiceDemo\Auth\Api\Pub\Signup;

use Continuous\MicroServiceDemo\Auth\Api\Main;
use Crudy\Server\Crud\CreateInterface;
use Crudy\Server\JsonApi\Exception;

class Create extends Main implements CreateInterface {

    public function create()
    {
        $attribute = $this->view->getAttributes();

        if (empty($attribute->nickname) || empty($attribute->password) ) {
            throw new Exception('Bad input value', 400);
        }

        $user = $this->query('user', 'uuid', $attribute->nickname);

        if (null !== $user) {
            throw new Exception('This nickname already exist', 400);
        }

        $this->insert('user', [
            'uuid' => $attribute->nickname,
            'password' => password_hash($attribute->password, PASSWORD_BCRYPT),
            'permission' => 'customer',
        ]);

        $this->createResource($attribute->nickname, [
            'nickname' => $attribute->nickname,
        ]);
    }
}
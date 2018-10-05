<?php

namespace App\Exceptions;

class RealmNameNotFound extends CustomException
{
    protected $message = ['realm' => 'Realm not found'];
    protected $code = 'REALM_NOT_FOUND';
    protected $data = [];
    protected $statusCode = 404;

}

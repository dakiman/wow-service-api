<?php

namespace App\Exceptions;

class RealmNameNotFound extends ApiException
{
    protected $message = ['realm' => 'Realm not found'];
    protected $code = 'REALM_NOT_FOUND';
    protected $data = [];
    protected $statusCode = 404;

}

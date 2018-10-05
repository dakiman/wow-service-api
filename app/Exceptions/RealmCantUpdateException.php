<?php

namespace App\Exceptions;

class RealmCantUpdateException extends CustomException
{
    protected $message = ['realms' => 'Realms cannot be updated.'];
    protected $code = 'REALM_UPDATE_FAILED';
    protected $data = [];
    protected $statusCode = 400;

}

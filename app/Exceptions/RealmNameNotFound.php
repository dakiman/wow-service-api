<?php

namespace App\Exceptions;

class RealmNameNotFound extends CustomException
{
    protected $message = "Realm not found";
    protected $code = 404;
    protected $data = [];

}

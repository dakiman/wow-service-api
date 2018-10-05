<?php

namespace App\Exceptions;

class RealmCantUpdateException extends CustomException
{
    protected $message = "Realms cannot be updated.";
    protected $code = 400;
    protected $data = [];

}

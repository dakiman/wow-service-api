<?php

namespace App\Exceptions;

use Exception;

class CharacterNotOwnedException extends ApiException
{
    protected $message = 'Character could not be found';
    protected $code = 'CHARACTER_FETCH_FAILED';
    protected $data = [];
    protected $statusCode = 403;
}

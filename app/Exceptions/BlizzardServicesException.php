<?php

namespace App\Exceptions;


class BlizzardServicesException extends ApiException
{
    protected $message = 'Blizzard services failed to respond.';
    protected $code = 'BLIZZARD_SERVICE_FAILURE';
    protected $data = [];
    protected $statusCode = 500;
}

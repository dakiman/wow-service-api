<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/15/2018
 * Time: 3:51 PM
 */

namespace App\Exceptions;


class AuthenticationException extends ApiException
{
    protected $message = 'Wrong email or password.';
    protected $code = 'AUTHENTICATION_NOT_SUCCESSFUL';
    protected $data = [];
    protected $statusCode = 400;
}
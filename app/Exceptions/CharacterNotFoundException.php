<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/5/2018
 * Time: 4:28 PM
 */

namespace App\Exceptions;


class CharacterNotFoundException extends ApiException
{
    protected $message = 'Character could not be found';
    protected $code = 'CHARACTER_FETCH_FAILED';
    protected $data = [];
    protected $statusCode = 404;
}
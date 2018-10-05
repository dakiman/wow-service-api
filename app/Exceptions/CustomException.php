<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/5/2018
 * Time: 12:14 PM
 */

namespace App\Exceptions;


use Exception;

class CustomException extends Exception
{
    protected $data = [];

    public function withCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function withData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function render()
    {
        return response(['errors' => $this->getMessage(), 'data' => $this->data], $this->getCode());
    }
}
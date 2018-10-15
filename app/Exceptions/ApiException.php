<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/5/2018
 * Time: 12:14 PM
 */

namespace App\Exceptions;


use Exception;

class ApiException extends Exception
{
    protected $data = [];
    protected $statusCode = 500;

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

    public function withMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function getData() {
        return $this->data;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }



    public function render()
    {
        return response()->api($this->data, $this->statusCode, [['code' => $this->getCode(), 'message' => $this->message]]);
    }
}
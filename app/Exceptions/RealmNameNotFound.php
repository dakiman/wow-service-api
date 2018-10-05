<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class RealmNameNotFound extends Exception
{
    protected $message = "Realm not found";
    protected $code = 404;
    protected $data = [];

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function withCode($code) {
        $this->code = $code;
        return $this;
    }

    public function withData($data) {
        $this->data = $data;
        return $this;
    }

    public function render()
    {
        return response(['errors' => $this->getMessage(), 'data' => $this->data], $this->getCode());
    }
}

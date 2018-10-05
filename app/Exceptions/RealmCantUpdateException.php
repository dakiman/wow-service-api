<?php

namespace App\Exceptions;

use Exception;

class RealmCantUpdateException extends Exception
{
    public function render()
    {
        return response(['error' => $this->getMessage()], 400);
    }
}

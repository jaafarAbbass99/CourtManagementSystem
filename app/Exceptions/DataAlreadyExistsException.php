<?php

namespace App\Exceptions;

use Exception;

class DataAlreadyExistsException extends Exception
{
    public function __construct($message = "البيانات موجودة مسبقًا.", $code = 409)
    {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}

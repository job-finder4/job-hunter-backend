<?php

namespace App\Exceptions;

use Exception;

class MyModelNotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'errors'=>[
                'code'=>404,
                'description'=>$this->getMessage(),
            ]
        ],404);
    }
}

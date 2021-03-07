<?php

namespace App\Exceptions;

use Exception;

class FileSizeMismatchException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'errors'=>[
                'code'=>422,
                'description'=>'the size of uploaded file is not accepted',
            ]
        ],422);
    }
}

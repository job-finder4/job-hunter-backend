<?php

namespace App\Exceptions;

use Exception;

class InvalidJobadApplicationException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'errors'=>[
                'code'=>422,
                'description'=>$this->getMessage(),
            ]
        ],422);
    }


    public static function alreadyExists()
    {
        return new static('you have already an application for this job');
    }

}

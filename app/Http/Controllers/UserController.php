<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAuthUser(){
        $user = auth('api')->user();
        return response($user,200);
    }
}

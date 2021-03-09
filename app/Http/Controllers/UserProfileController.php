<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserProfileController extends Controller
{
    public function store(Request $request)
    {
        auth()->user()->setProfile($request->except('visibility'),$request->only('visibility'));

        return response()->json([],201);
    }

}

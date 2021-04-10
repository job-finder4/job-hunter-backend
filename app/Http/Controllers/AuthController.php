<?php

namespace App\Http\Controllers;

use App\Http\Resources\Jobad as JobadResource;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

class AuthController extends Controller
{
    public function registerAsJobSeeker(Request $request)
    {
        $user = $this->generalRegister($request);

        $user->assignRole('jobSeeker');
        return response(new UserResource($user), 201);
    }
    public function registerAsCompany(Request $request)
    {
        $user = $this->generalRegister($request);
        $user->assignRole('company');
        return response(new UserResource($user), 201);
    }

    public function generalRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return $user;
    }
}

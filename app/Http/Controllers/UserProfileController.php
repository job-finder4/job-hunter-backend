<?php

namespace App\Http\Controllers;

use App\Http\Resources\Profile as ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        return response()->json(new ProfileResource($user->profile),200);
    }

    public function store(Request $request)
    {
        $profile = auth()->user()->addProfileDetails($request);

        return response()->json(new ProfileResource($profile),201);
    }

    public function update(Request $request)
    {

        $request->validate([
            'details' => '',
            'visible' => ''
        ]);

        $profile = auth()->user()->profile;

        if ($request->exists('details'))
        {
             $profile->details = $profile->details->update($request->details);
        }
        if ($request->exists('visible'))
        {
            $profile->visible = $request->visible;
        }
        $profile->save();
    }


}

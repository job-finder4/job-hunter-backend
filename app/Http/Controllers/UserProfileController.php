<?php

namespace App\Http\Controllers;

use App\Http\Resources\Profile as ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:create,App\Models\Profile')->only('store');
        $this->middleware('can:update,profile')->only('update');
    }

    public function store(Request $request)
    {
        $profile = auth()->user()->addProfileDetails($request->all());

        return response()->json(new ProfileResource($profile), 201);
    }

    public function update(Request $request,User $user,Profile $profile)
    {
        $request->validate([
            'details' => '',
            'visible' => ''
        ]);

        $profile = $user->profile;

        if ($request->exists('details')) {
            $profile->details = $profile->details->update($request->details);
        }

        if ($request->exists('visible')) {
            $profile->visible = $request->visible;
        }

        $profile->saveOrFail();
    }


}

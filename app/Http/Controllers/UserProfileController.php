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
//        $this->middleware('can:create,App\Models\Profile')->only('store');
//        $this->middleware('can:update,App\Models\Profile,user')->only('update');
//        $this->middleware('can:view,App\Models\Profile,user')->only('show');
    }

    public function show(User $user)
    {
        $profile = $user->profile()->with('user', 'user.skills')->firstOrFail();

        return response()->json(new ProfileResource($profile), 200);
    }

    public function store(Request $request)
    {
        $profile = $request->user()->addProfileDetails($request);

        return response()->json(new ProfileResource($profile->fresh()), 201);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'details' => '',
            'visible' => '',
            'skills' => ''
        ]);

        $profile = Profile::where('user_id',$request->user()->id)->firstOrFail();

        $shouldUpdate = [];

        if ($request->exists('details')) {
            $shouldUpdate['details'] = serialize($profile->details->update($request->details));
        }

        if ($request->exists('visible')) {
            $shouldUpdate['visible'] = $request->visible;
        }

        if ($request->exists('skills')) {
            $request->user()->skills()->sync($request->skills);
        }

        Profile::where('user_id',$request->user()->id)->update($shouldUpdate);

        return response(new ProfileResource($profile->fresh()),200);
    }

    public function deleteItems(Request $request)
    {
        $request->validate([
            'details' => ['required','array']
        ]);
        $profile = $request->user()->deleteProfileDetails($request);
        return response(new ProfileResource($profile->fresh()),200);
    }

}

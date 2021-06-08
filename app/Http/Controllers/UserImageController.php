<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserImageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => [
                'required',
                'image',
            ]
        ]);
//        Rule::dimensions()->height(70)->width(70)

        $user = $request->user();

        $user->deleteOldImage();

        $image = $user->storeImage($data['image']);

        return response()->json(['image' => url($image->path)], 201);
    }

    public function destroy(Request $request)
    {
        $request->user()->deleteOldImage();

        return response()->json([], 200);
    }

}

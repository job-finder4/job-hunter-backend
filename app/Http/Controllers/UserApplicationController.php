<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationCollection;
use App\Models\User;
use Illuminate\Http\Request;

class UserApplicationController extends Controller
{
    public function index(User $user)
    {
        return response()->json(
            new ApplicationCollection($user->applications()->orderByDesc('updated_at')->get())
            ,200);
    }
}

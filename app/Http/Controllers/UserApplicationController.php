<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationCollection;
use App\Models\User;
use Illuminate\Http\Request;

class UserApplicationController extends Controller
{
    public function index(User $user,Request $request)
    {
    	$allApplications = $user->applications()->get();
    	
        if($request->has('filter')){
            $filter=$request->filter;
            if ($filter == 'rejected') {
                $allApplications = $user->applications()->where('status', -1)->orderByDesc('updated_at')->get();
            }
            if ($filter == 'approved') {
                $allApplications = $user->applications()->where('status', 1)->orderByDesc('updated_at')->get();
            }
            if ($filter == 'pending') {
                $allApplications = $user->applications()->where('status', 0)->orderByDesc('updated_at')->get();
            }
        }

        return response()->json(new ApplicationCollection($allApplications), 200);
    }
}

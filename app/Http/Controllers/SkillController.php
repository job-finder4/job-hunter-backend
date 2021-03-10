<?php

namespace App\Http\Controllers;

use App\Http\Resources\Skill as SkillResource;
use App\Http\Resources\SkillCollection;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'parent_id' => ''
        ]);

        $skill = Skill::create($data);
        return new SkillResource($skill);
    }

    public function index()
    {
        return response(new SkillCollection(Skill::get()), 200);
    }
}

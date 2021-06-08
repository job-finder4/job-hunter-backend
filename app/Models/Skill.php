<?php

namespace App\Models;

use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Kalnoy\Nestedset\NodeTrait;
use phpDocumentor\Reflection\Types\This;

class Skill extends Model
{
    use HasFactory, NodeTrait, FullTextSearch;

    protected $guarded = [];

    protected $searchable = [
        'name'
    ];

    public function jobads()
    {
        return $this->morphedByMany('App\Models\Jobad', 'skillable');
    }

    public function users(){
        return $this->morphedByMany('App\Models\User', 'skillable');
    }



//    public static function customizeSearchTerm(array $term): string
//    {
//        $skills = Skill::query()->search($term)->with('descendants')->get();
//
//        $reservedSymbols = ['-', '+', '>', '<', '@', '(', ')', '~'];
//
//        $descendantSkills = [];
//
//        $skills->each(function ($skill) use (& $descendantSkills,$reservedSymbols) {
//            $skill->descendants->each(function ($descendant) use (& $descendantSkills,$reservedSymbols) {
//                if (strlen($descendant->name) >= 3)
//                    $descendantSkills[] = str_replace($reservedSymbols,'',$descendant->name);
//            });
//        });
//
//        $descendantSkills = explode(' ',implode(' ',$descendantSkills));
//
//        $newTerm =  implode(' ', array_merge($descendantSkills,$term));
//
//        return $newTerm;
//    }

}

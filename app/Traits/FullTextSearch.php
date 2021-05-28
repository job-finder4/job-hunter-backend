<?php

namespace App\Traits;

use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait FullTextSearch
{
    public function fullTextWildcards(String $term)
    {

        $reservedSymbols = ['-', '+', '>', '<', '@', '(', ')', '~', '&'];

        $term = explode(' ', $term);

        $newWords = [];

        foreach ($term as $key => $word) {
            if (strlen($word) >= 3) {
                $newWord = str_replace($reservedSymbols, '', $word);

                $newWords[] = $newWord . '*';
            }
        }

        $searchTerm = implode(' ', $newWords);

        return $searchTerm;
    }


    public function scopeSearch(Builder $query, String $term)
    {
        $columns = implode(' ', $this->searchable);

        $searchableTerm = $this->fullTextWildcards($term);

        return
            $query
                ->selectRaw(
                    "MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE) AS score",
                    [$searchableTerm]
                )
                ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
                ->orderByDesc('score');
    }

}

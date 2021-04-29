<?php

namespace App\Traits;

use App\Models\Jobad;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait FullTextSearch
{
    public function fullTextWildcards(Array $term)
    {

        $reservedSymbols = ['-', '+', '>', '<', '@', '(', ')', '~'];

        $term = explode(' ',implode(' ',$term));

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


    public function scopeSearch(Builder $query,Array $term)
    {
        $columns = implode(' ', $this->searchable);

        $searchableTerm = $this->fullTextWildcards($term);

        $res = $query->selectRaw('*')
            ->selectRaw(
                "MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE) AS score",
                [$searchableTerm]
            )
            ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $searchableTerm)
            ->orderByDesc('score');
    }

}

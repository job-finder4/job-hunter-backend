<?php

namespace App\GraphQL\Queries;


use App\Models\Project;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ProjectsQuery extends Query
{
    protected $attributes = [
        'name' => 'projects',
        'description' => 'show list of projects'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Project'));
    }

    public function args(): array
    {
        return [
          'projectId' => [
              'type' => Type::int()
          ]
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['projectId'])){
            return Project::where('id',$args['projectId'])->get();
        }
        return Project::all();
    }
}

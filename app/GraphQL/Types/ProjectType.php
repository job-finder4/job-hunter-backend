<?php

namespace App\GraphQL\Types;

use App\Models\Project;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProjectType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Project',
        'description' => 'A Project Type',
        'model' => Project::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'project id'
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'project title'
            ],
            'description' => [
                'type' => Type::nonNull(Type::string())
            ],
            'team' => [
                'type' => Type::nonNull(Type::listOf(Type::nonNull(GraphQL::type('User'))))
            ],
            'manager' => [
                'type' => Type::nonNull(GraphQL::type('User'))
            ],
            'tasks' => [
                'type' => Type::listOf(Type::nonNull(GraphQL::type('Task')))
            ]
        ];
    }

    public function resolveTeamField($root, $args)
    {
        return $root->users;
    }

}

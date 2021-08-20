<?php


namespace App\GraphQL\Types;

use App\Models\Task;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TaskType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Task',
        'description' => 'a Task Type',
        'model' => Task::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
            ],
            'title' => [
                'type' => Type::nonNull(Type::string())
            ],
            'description' => [
                'type' => Type::nonNull(Type::string())
            ],
            'statusCode' => [
                'type' => Type::nonNull(Type::string())
            ],
            'points' => [
                'type' => Type::nonNull(Type::float())
            ],
            'expectedWorkHours' => [
                'type' => Type::nonNull(Type::int())
            ],
            'startedAt' => [
              'type' => Type::string()
            ],
            'finishedAt' => [
                'type' => Type::string()
            ],
            'createdAt' => [
                'type' => Type::nonNull(Type::string())
            ],
            'user' => [
                'type' => GraphQL::type('User')
            ],
        ];
    }

    public function resolveStatusCodeField($root, $args)
    {
        return $root->status_code;
    }

    public function resolveStartedAtField($root, $args)
    {
        return optional($root->started_at)->toFormattedDateString();
    }

    public function resolveFinishedAtField($root, $args)
    {
        return optional($root->finished_at)->toFormattedDateString();
    }

    public function resolveExpectedWorkHoursField($root, $args)
    {
        return $root->expected_work_hours;
    }

    public function resolveCreatedAtField($root, $args)
    {
        return $root->created_at->diffForHumans();
    }

}

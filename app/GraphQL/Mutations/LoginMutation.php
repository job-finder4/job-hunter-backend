<?php


namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GuzzleHttp\Client;
use Rebing\GraphQL\Support\Mutation;

class LoginMutation extends Mutation
{

    protected $attributes = [
        'name' => 'login',
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            'email' => [
                'type' => Type::string(),
                'rules' => ['required', 'email'],
            ],
            'password' => [
                'type' => Type::string(),
                'rules' => ['required'],
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $client = new Client;
        $response = $client->post('oauth/token', [
            'form_params' => [
                'grant_type' => 'password-grant',
                'user_name' => $args['email'],
                'password' => $args['password'],
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            ]]);
        return null;
    }
}

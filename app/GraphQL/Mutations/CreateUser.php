<?php

namespace App\GraphQL\Mutations;

use Faker\Factory;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class CreateUser
{
    public function __invoke ($_, array $args) {
        $faker = Factory::create();
        $user = [
            'id'         => rand(),
            'name'       => $faker->name(),
            'email'      => $faker->email,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        # Subscription::broadcast('userCreated', $user);

        return $user;
    }
}

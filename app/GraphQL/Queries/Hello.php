<?php

namespace App\GraphQL\Queries;

class Hello
{
    public function __invoke ($_, array $args) {
        return 'Seriously?! You\'re trying to run this?';
    }
}

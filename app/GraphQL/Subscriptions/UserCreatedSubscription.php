<?php

namespace App\GraphQL\Subscriptions;

use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

class UserCreatedSubscription extends GraphQLSubscription
{
    public function authorize (Subscriber $subscriber, Request $request) : bool {
        return true;
    }

    public function filter (Subscriber $subscriber, $root) : bool {
        return true;
    }
}

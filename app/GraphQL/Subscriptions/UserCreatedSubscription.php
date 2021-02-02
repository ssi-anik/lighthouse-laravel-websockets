<?php

namespace App\GraphQL\Subscriptions;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserCreatedSubscription extends GraphQLSubscription
{
    /**
     * Level 1 Authorization: Expand this comment
     *
     * This happens when running the graphql query. If this return `false`
     * The user won't be registered in the subscription registry.
     * vendor/nuwave/lighthouse/src/Subscriptions/SubscriptionResolverProvider.php
     * @provideSubscriptionResolver -> resolver closure -> is checked before registering the subscriber
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     *
     * @return bool
     */
    public function can (Subscriber $subscriber) {
        // $user = $subscriber->context->user();
        // check if the user can subscribe or not?
        // has access to the subscription arguments, can check on this as well.
        return ($subscriber->args['name'] ?? null) == 'anik';
    }

    /**
     * Level 2 Authorization: Expand this comment
     *
     * This happens when the echo package sends the authentication request. `graphql/subscriptions/auth`
     * vendor/nuwave/lighthouse/src/Subscriptions/SubscriptionRouter.php@pusher
     * -> vendor/nuwave/lighthouse/src/Support/Http/Controllers/SubscriptionController.php@authorize
     * -> vendor/nuwave/lighthouse/src/Subscriptions/SubscriptionBroadcaster.php@authorize
     * -> vendor/nuwave/lighthouse/src/Subscriptions/Authorizer.php@authorize
     * $subscriber is the user who was subscribed due to `can` method,
     * $request is the current form request of the request.
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     * @param \Illuminate\Http\Request                    $request
     *
     * @return bool
     */
    public function authorize (Subscriber $subscriber, Request $request) : bool {
        // $user = $subscriber->context->user();
        // $authUser = $request->user();
        // this only validate `graphql/subscriptions/auth` url call
        // returning false will not let user to connect to the channel programmatically via laravel-echo
        return true;
    }

    /**
     * Filter if you don't want to broadcast a topic to a subscriber
     * As if you're updating the post and don't want to be notified,
     * In those cases, you can filter out who should not receive
     * the broadcast from here. return false in those cases.
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     * @param                                             $root
     *
     * @return bool
     */
    public function filter (Subscriber $subscriber, $root) : bool {
        return true;
    }

    /**
     * If you want to tweak the topic name
     *
     * @param \Nuwave\Lighthouse\Subscriptions\Subscriber $subscriber
     * @param string                                      $fieldName
     *
     * @return string
     */
    public function encodeTopic (Subscriber $subscriber, string $fieldName) {
        return parent::encodeTopic($subscriber, $fieldName);
    }

    /**
     * If you encoded a topic, decode it accordingly
     *
     * @param string $fieldName
     * @param        $root
     *
     * @return string
     */
    public function decodeTopic (string $fieldName, $root) {
        return parent::decodeTopic($fieldName, $root);
    }

    /**
     * You can load your relation or mutate the data before it's being sent to the subscriber
     *
     * @param                                                     $root
     * @param array                                               $args
     * @param \Nuwave\Lighthouse\Support\Contracts\GraphQLContext $context
     * @param \GraphQL\Type\Definition\ResolveInfo                $resolveInfo
     */
    public function resolve ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) {
        $root['pushed_by_subs_resolver'] = 'obviously by UserCreatedSubscription';

        return $root;
    }
}

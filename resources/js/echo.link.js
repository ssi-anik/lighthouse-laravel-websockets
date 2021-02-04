import {ApolloLink, Observable} from 'apollo-link';
import Echo from 'laravel-echo/dist/echo';

class EchoLink extends ApolloLink {
    constructor () {
        super();
        const token = localStorage.getItem('token');
        this.subscriptions = [];
        this.echo = new Echo({
            broadcaster: 'socket.io',
            authEndpoint: `graphql/subscriptions/auth`,
            // Change the host name and build the js files
            host: '127.0.0.1:9007',
            auth: {
                headers: {
                    authorization: token ? `Bearer ${token}` : null,
                },
            },
        });
    }

    request (operation, forward) {
        return new Observable(observer => {
            // Check the result of the operation
            forward(operation).subscribe({
                next: data => {
                    // If the operation has the subscription extension, it's a subscription
                    const subscriptionChannel = this._getChannel(data, operation);

                    if ( subscriptionChannel ) {
                        this._createSubscription(subscriptionChannel, observer);
                    } else {
                        // No subscription found in the response, pipe data through
                        observer.next(data);
                        observer.complete();
                    }
                },
            });
        });
    }

    _getChannel (data, operation) {
        return !!data.extensions && !!data.extensions.lighthouse_subscriptions && !!data.extensions.lighthouse_subscriptions.channels ? data.extensions.lighthouse_subscriptions.channels[operation.operationName] : null;
    }

    _createSubscription (subscriptionChannel, observer) {

        if ( !this.subscriptions.find(s => s.channel === subscriptionChannel) ) {
            this.subscriptions.push({
                channel: subscriptionChannel, observer: observer,
            });
        }

        this.echo.join(subscriptionChannel).listen('.lighthouse.subscription', payload => {
            console.log('received subscription payload: ' + JSON.stringify(payload));
            if ( observer._subscription._state === 'closed' ) {
                this._leaveSubscription(subscriptionChannel, observer);
                return;
            }

            const result = payload.data;
            if ( result ) {
                observer.next({
                    data: result.data, extensions: result.extensions,
                });
            }
        });
    }

    _leaveSubscription (channel, observer) {
        const subscription = this.subscriptions.find(s => s.channel === channel);
        this.echo.leave(channel);
        observer.complete();
        this.subscriptions = this.subscriptions.slice(this.subscriptions.indexOf(subscription), 1);
    }
}

export default EchoLink;

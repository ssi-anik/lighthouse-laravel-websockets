import VueApollo from 'vue-apollo';
import ApolloClient from 'apollo-client';
import {BatchHttpLink} from 'apollo-link-batch-http';
import {InMemoryCache} from 'apollo-cache-inmemory';
import {ApolloLink} from 'apollo-link';
import PusherLink from './pusher.link';
//import EchoLink from './echo.link';

const echoLink = new PusherLink();
//const echoLink = new EchoLink();

// Echo driver is enabled in another project. the URI points to that project.
const uri = '/graphql';
//const uri = 'http://127.0.0.1:9999/graphql';

const cache = new InMemoryCache();
const batchHttpLink = new BatchHttpLink({
    uri, fetch: async (uri, options) => {
        const token = localStorage.getItem('token');
        return fetch(uri, options);
    },
});

const apolloClient = new ApolloClient({
    link: ApolloLink.from([
        echoLink,
        batchHttpLink
    ]), cache, defaultOptions: {
        watchQuery: {
            errorPolicy: 'all',
        }, query: {
            errorPolicy: 'all',
        },
    },
});

export const apollo = new VueApollo({
    defaultClient: apolloClient,
});

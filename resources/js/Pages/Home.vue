<template>
  <layout>
    <div class="flex flex-col items-center p-8">
      <ApolloMutation :mutation="$options.mutations.createUser">
        <template v-slot="{ mutate }">
          <button @click="mutate" class="p-2 bg-purple-500 text-white rounded">Run create user mutation</button>
        </template>
      </ApolloMutation>

      <div class="p-8">
        <label>Subscription response:</label> <span>{{ subscriptionValue }}</span>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import gql from 'graphql-tag';

export default {
  name: 'Home',
  components: { Layout },
  data() {
    return {
        subscriptionValue: 'Click the above button!',
    };
  },
  mutations: {
      createUser: gql`
      mutation createUser {
        createUser {
            id
            name
        }
      }
    `,
  },
  apollo: {
    $subscribe: {
      subscribed: {
        query: gql`
          subscription userCreated {
            userCreated (name: "anik") {
              id
              name
              email
              created_at
            }
          }
        `,
        result({ data }) {
          this.subscriptionValue = data.userCreated;
        },
      },
    },
  },
};
</script>

<style scoped></style>

# Lighthouse - Laravel Websockets integration example

This project contains the implementation of Lighthouse subscription with pusher/laravel-websockets. If you're looking for the implementation of `echo` driver, then check the implementation in this [repository](https://github.com/ssi-anik/confession-wall).

## Version
This repository contains Laravel `8.x` and Lighthouse `5.2.x`

## Dependencies, requirements and build tools
This project comes with `docker` & `docker-compose`. But to minimize the boot up time when you try `docker-compose up -d --build` the local files are mounted to application & worker containers and **NOT COPIED TO CONTAINERS**.
Thus, it's recommended to use PHP & composer locally. Resolve your project dependency before you run your application using `composer install`.
But you need `node` environment to build the JS dependency if you're not using pusher but the `echo` driver for subscription. Follow the attached article.

## How to use?
- Clone the repository.
- `cp docker-compose.yml.example docker-compose.yml`.
- Make the required changes to your `docker-compose.yml`.
- `cp .env.example .env`.
- Make the required changes to your `.env`.
- Run `php artisan key:generate` to generate application key.
- `touch database/database.sqlite` to create sqlite database.
- `php artisan migrate` to migrate the database.
- `yarn install` to install the dependencies.
- `yarn run watch` to build the JS files.
- `docker-compose up -d --build` to build your containers.
- Loading `http://127.0.0.1:{PHP_PORT}` in your browser will return a json response.
- If you don't have `composer` locally, then `exec`-ing to php container after containers are up and install the dependencies will work. Just restart the containers.
- If you're changing the environment variables, make sure to change them other places and run commands if required.

## Working with pusher/laravel-websockets
- Open [served](http://127.0.0.1:8000/) application in your browser. It's on port `8000`. Change the port if you changed in the `docker-compose.yml`'s php port.
- Open [playground](http://127.0.0.1:8000/graphql-playground) url in your browser. It's on port `8000`. Change the port if you changed the `docker-compose.yml`'s php port.
- Check the browser's console log if everything is okay or not.
- Run the following mutation from the playground.
```graphql endpoint doc
mutation createUser{
  createUser (name: "A")  {
    id
    name
    email
    created_at
    updated_at
    has_arg
    pushed_by_subs_resolver
  }
}
```
- You'll get to see the update in your application homepage.
- Otherwise, click the button on the homepage, you'll see the value gets updated.

## Working with `echo` driver
- Comment the pusher related variables and imports in `resources/js/apollo.js`.
- Uncomment and change the url that points to the other project that has `echo` driver implemented in `resources/js/apollo.js`.
- Copy a JWT token from the other project's login mutation.
- Open inspect element.
- Add the copied jwt token in your page's `localStorage` with the key `token`. 
- Build the project with `yarn run watch`.
- Reload the page.
- Click the button. 

Wait for a few seconds as the other project uses queue to push the data to echo server (by default). And you should be able to see the data changing.

## GraphQL Related Articles
1. [GraphQL by night — Understanding GraphQL Basics](https://bit.ly/2LxF7xX)
2. [GraphQL by night — Implementing GraphQL with Laravel/Lumen](https://bit.ly/3cYD15K)
3. [GraphQL by night — Implementing GraphQL Subscription with Laravel/Lumen](https://bit.ly/3jFm1mo) in [nuwave/lighthouse](https://github.com/nuwave/lighthouse) using Pusher & Echo driver

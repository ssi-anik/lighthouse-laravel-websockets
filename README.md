# Lighthouse - Laravel Websockets integration example

- `cd` into this project in your machine.
- `composer install`
- `touch database/database.sqlite`
- `cp .env.example .env`
- If you don't want to build the js code, don't change your pusher values. Otherwise, change pusher values in `resources/js/echo.link.js` according to your `.env` and install js dependencies and run `yarn run watch` or `yarn run dev`. Can use `npm` if not `yarn`ing.
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan websockets:serve`
- `php artisan serve`

- Open [served](http://127.0.0.1:8000/) application in your browser. It's on port `8000`
- Open [playground](http://127.0.0.1:8000/graphql-playground) url in your browser. It'll be on `8000` port. If your application is served on different port, change it too.
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
- Otherwise, click the button on the homepage, you'll see the subscribed values.

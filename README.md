## About This Project

Azhar's starter pack for creating Laravel applications; And it is (sort of, still) using monorepo approach.

There's not yet plan for advanced implementation such as SSO, microservice, etc. Just a tiny starter pack; Hence vertical scaling -- At least for now.

Note for myself: Please create new branch for new feature / new-new branch for the new feature features / and so on. As I will think and read about it later.

## Running This Project

1. Clone the repo if available.

2. Install composer dependency.

```
$ composer install
```

3. Copy the env.

```
$ cp .env.example .env
```

Don't forget to change the database config.

4. Generate Laravel key.

```
$ php artisan key:generate
```

5. Generate JWT secret.

```
$ php artisan jwt:secret
```

6. Run the seeder.

```
$ php artisan migrate:fresh --seed
```

7. Install npm dependency.

```
$ npm ci
```

8. Build asset.

```
$ php artisan view:clear && npm run build && php artisan view:clear
```

9. Laravel `php artisan serve` might be broken as everything relied on API usage (CURL timeout, etc) -- Idk why tho. So try this boilerplate using something that supports a virtual host, such as Laragon, etc.

## Extras

1. Some features (email, websocket, etc) rely on a queue. Make sure the queue is working in the background.

```
$ php artisan queue:work
```

The queue in the background can be managed using third-party tools, such as [Supervisor](https://supervisord.org/) or [PM2](https://pm2.keymetrics.io/).

2. When working with general asset like component / feature like websocket, or any other QOL feature; Please do it inside main repo so that the entire project can get the benefit.

3. For a feature, please:

- Create new controller inside the `feat` folder with:

```
$ php artisan make:controller API/Feat/xxx/xxxController

and/or

$ php artisan make:controller FE/Feat/xxx/xxxController
```

- Place new repo inside the `feat` folder (`app\Contracts\Feat` and `app\Repositories\Feat`).

- Create new model inside the `feat` folder with:

```
$ php artisan make:model Feat/xxx
```

So that it will have minimal impact on the main branch.

4. There's a new feature which is "Cloudflare D1" as a database driver by [Erimeilis](https://github.com/erimeilis/laravel-cloudflare-d1). But to be honest, I don't think it's good enough to be used as a main driver.

```
Reason:

- Single-threaded, so it processes queries one at a time.
- Must implement sharding if the project becomes too large, because the maximum size of one the database is 10 GB per database.
- Manual sharding implementation is required but hard to implement because there are no tools available, for now, at least, probably.
```

But if you're interested, here's the reference:

```
Reference:

- Migration: database\migrations\0001_01_02_000003_create_d1_test_table.php
- Model: app\Models\D1Test.php
- Config: config\cloudflare-d1.php
```

It needs to be known that the connection must be explicitly declared, or the connection will error.

For now, I think the best use-case for Cloudflare D1 is to use it as a database driver that handles unimportant data such as queues, logs and other miscellaneous data if the project is too large.

But if the project is small to medium, or not really that important (e.g: a landing page), then it is perfectly fine to use. Since other free databases like Supabase will deactivate the database if inactive for a period of time.

## Roadmap

1. ~~RBAC => Done~~
- ~~Role~~
- ~~Permission~~
- ~~UAC~~

2. Access => 50%
- Dynamic Menu (~~Backend logic is done~~; No GUI / Form yet)

3. ~~Auth (Mailable via queue) => Done~~
- ~~Register~~
- ~~Login~~
- ~~Verify~~
- ~~Reset Password~~
- SSO (Need to learn later)
- OAuth (Need to learn later)

4. ~~Websocket  => 100% => Done~~ => Example @ \app\Http\Controllers\API\Core\Access\PermissionController.php
- ~~Pusher~~
- ~~Soketi~~

5. Payment Gateway => 0%
- Midtrans
- Tripay
- Crypto-related

6. Caching => 0%
- Redis

93. Infra
- n8n
- Docker
- FrankenPHP

94. ~~Email fallback (Brevo, Zepto) => Done~~
- ~~Multiple provider~~

95. CI/CD
- What?

96. Database => 0%
- Need to replicate? => Do it with Postgre replication.
- Need to cluster? => Do it with Postgre + Patroni + HAProxy.
- ~~Need to sharding? No.~~

97. High availability => 0%
- HAProxy seems good and reliable.

98. Split the frontend & backend
- Rn still monolith.
- Idk perhaps learn about microservice?

99. Unit Test => 0%
- Well, well..?
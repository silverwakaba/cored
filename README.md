## About This Project

Azhar's starter pack for creating Laravel applications; And it is (sort of) using monorepo approach.

There's not yet plan for advanced implementation such as SSO, microservice, etc. Just a tiny starter pack; Hence vertical scaling -- At least for now.

Note for myself: Please create new branch for new feature / new-new branch for the new feature features / and so on. As I will think and read about it later.

## Running This Project

1. Clone the repo if available.

2. Install composer dependency.

```
composer install
```

3. Copy the env.

```
cp .env.example .env
```

And change the database config too.

4. Generate Laravel key.

```
php artisan key:generate
```

5. Generate JWT secret.

```
php artisan jwt:secret
```

6. Run the seeder.

```
php artisan migrate:fresh --seed
```

7. Install npm dependency.

```
npm ci
```

8. Build asset.

```
php artisan view:clear && npm run build && php artisan view:clear
```

9. Laravel `php artisan serve` might be broken as everything relied on API usage (CURL timeout, etc) -- Idk why tho. So try this boilerplate using something that supports a virtual host, such as Laragon, etc.

## Extras

1. Some features (email, websocket, etc) rely on a queue. Make sure the queue is working in the background.

```
php artisan queue:work
```

The queue in the background can be managed using third-party tools, such as [Supervisor](https://supervisord.org/) or [PM2](https://pm2.keymetrics.io/).

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
- Need to replicate? => Postgre replication.
- Need to cluster? => Postgre + Patroni + HAProxy.
- ~~Need to sharding? No.~~

97. High availability => 0%
- HAProxy.

98. Split the frontend & backend
- Rn still monolith.
- Idk perhaps learn about microservice?

99. Unit Test => 0%
- Well, well..?
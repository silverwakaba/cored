## About This Project

Azhar's starter pack for creating Laravel applications; And it is (sort of) using monorepo approach.

There's not yet plan for advanced implementation such as SSO, microservice, etc. Just a tiny starter pack; Hence vertical scaling -- At least for now.

Note for myself: Please create new branch for new feature / new-new branch for the new feature features / and so on. As I will think and read about it later.

## Running This Project

0 - Clone the repo if available.

1 - Install composer dependency.

```
composer install
```

2 - Copy the env.

```
cp .env.example .env
```

And change the database config too.

3 - Generate Laravel key.

```
php artisan key:generate
```

4 - Generate JWT secret.

```
php artisan jwt:secret
```

5 - Run the seeder.

```
php artisan migrate:fresh --seed
```

6 - Install npm dependency.

```
npm ci
```

7 - Build asset.

```
npm run build
```

8 - Laravel `php artisan serve` might be broken as everything relied on API usage (CURL timeout, etc) -- Idk why tho.

## Roadmap

1. ~~RBAC => Done~~
- ~~Role~~
- ~~Permission~~
- ~~UAC~~

2. Access => 50%
- Dynamic Menu (Backend logic is done; No GUI / Form yet)

3. ~~Auth (Mailable via queue) => Done~~
- ~~Register~~
- ~~Login~~
- ~~Verify~~
- ~~Reset Password~~
- SSO (Need to learn)

4. Websocket  => 0%
- Pusher
- Soketi

5. Payment Gateway => 0%
- Midtrans
- Tripay

6. Caching => 0%
- Redis

95. CI/CD

96. Database replication => 0%
- Need to learn.

97. High availability => 0%
- Need to learn.

98. Split the frontend & backend
- Rn still monolith.
- Idk perhaps learn about microservice?

99. Unit Test => 0%
- Well, well..?
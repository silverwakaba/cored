## About This Project

Azhar's starter pack for creating Laravel applications; And it is (sort of) using monorepo approach.

Please create new branch for new feature / new-new branch for the new feature features / and so on.

We will talk about it later.

## Running This Project

0 - Clone the repo

1 - Install composer dependency

```
composer install
```

2 - Copy the env

```
cp .env.example .env
```

And change the database too.

3 - Generate Laravel key

```
php artisan key:generate
```

4 - Generate JWT secret

```
php artisan jwt:secret
```

5 - Run the seeder

```
php artisan migrate:fresh --seed
```

6 - Install npm dependency

```
npm ci
```

7 - Build asset

```
npm run build
```

8 - Laravel `php artisan serve` might be broken as everything relied on API usage (CURL timeout, etc).
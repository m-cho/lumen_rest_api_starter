# Lumen Rest API Starter

Lumen (v5.4) Rest API starter with User controller and model (id, first_name, last_name, email, password) and authentification.

```
git clone https://github.com/shugi-bugi/lumen_rest_api_starter.git
cd lumen_rest_api_starter
composer install
php -S localhost:8000 -t public
```

# Authentification
 * Authentification Header
    ```
    Authentification: Bearer jwt.access.token
    ```
or
  * Query string
    ```
    http://example.com/?access_token=jwt.access.token
    ```

# Routes
```
php artisan route:list
```

```
+------+------------------------+------------+-------------------------------------+----------+------------+
| Verb | Path                   | NamedRoute | Controller                          | Action   | Middleware |
+------+------------------------+------------+-------------------------------------+----------+------------+
| GET  | /                      |            | None                                | Closure  |            |
| GET  | /api/v1/users/me       |            | App\Http\Controllers\UserController | me       | auth       |
| POST | /api/v1/users/login    |            | App\Http\Controllers\UserController | login    |            |
| POST | /api/v1/users/register |            | App\Http\Controllers\UserController | register |            |
+------+------------------------+------------+-------------------------------------+----------+------------+
```

## Lumen Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

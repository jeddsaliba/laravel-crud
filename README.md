# Laravel CRUD

This project was generated with [Laravel](https://laravel.com/).

The purpose of this project is to showcase a Laravel Project with basic CRUD functionalities.

## Table of Contents
[Installation](#installation)<br/>
[Package(s) Used](#packages-used)<br/>
[Database](#database)<br/>
[Development Server](#development-server)<br/>
[Support](#support)

<a name="installation"></a>
## Installation
When using [Postman](https://www.postman.com/), use the Postman API Collection [here](https://github.com/jeddsaliba/laravel-crud/blob/master/Laravel_CRUD.postman_collection.json).

Install the `dependencies` by running:

```bash
composer install
```

<a name="packages-used"></a>
## Package(s) Used
- [Hashids](https://packagist.org/packages/hashids/hashids)
    - For Hashids, you can add and modify the following in your `.env`:
      ```bash
      HASH_SALT=s72GRGmT59LrNdU6vMVmjQKSahF9gJ9ll1Wjmm3wcBFj7op9ty
      HASH_MIN_LENGTH=12
      HASH_ALPHABET=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890
      ```

<a name="installation"></a>
## Database
Migrate database by running:

```bash
php artisan migrate
```

To populate and have sample data in your database, run this command:

```bash
php artisan migrate --seed
```

<a name="development-server"></a>
## Development Server
Run this command:

```bash
php artisan serve
```

<a name="support"></a>
## Support
For support, email developer.jeddsaliba@gmail.com.

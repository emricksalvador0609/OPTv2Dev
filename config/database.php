<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
        
        'mysql' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '172.16.0.199'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'encrypt' => env('DB_ENCRYPT'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],


        'budgeting' => [
            'driver' => env('DB_DRIVER_BUDGETING', 'mysql'),
            'host' => env('DB_HOST_BUDGETING', '52.221.154.99'),
            'port' => env('DB_PORT_BUDGETING', '3307'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_BUDGETING', 'forge'),
            'username' => env('DB_USERNAME_BUDGETING', 'forge'),
            'password' => env('DB_PASSWORD_BUDGETING', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'encrypt' => env('DB_ENCRYPT_BUDGETING'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE_BUDGETING'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'salesdata' => [
            'driver' => env('DB_DRIVER_SALESDATA', 'mysql'),
            'host' => env('DB_HOST_SALESDATA', '52.221.154.99'),
            'port' => env('DB_PORT_SALESDATA', '3307'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_SALESDATA', 'forge'),
            'username' => env('DB_USERNAME_SALESDATA', 'forge'),
            'password' => env('DB_PASSWORD_SALESDATA', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'encrypt' => env('DB_ENCRYPT_SALESDATA'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE_SALESDATA'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'PRD109' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_PRD109', '172.16.0.109'),
            'port' => env('DB_PORT_PRD109', '1433'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_PRD109', 'forge'),
            'username' => env('DB_USERNAME_PRD109', 'forge'),
            'password' => env('DB_PASSWORD_PRD109', ''),
            'encrypt' => env('DB_ENCRYPT_PRD109', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE_PRD109', 'no'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'OMSL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_OMSL', '52.220.134.160'),
            'port' => env('DB_PORT_OMSL', '1433'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_OMSL', 'forge'),
            'username' => env('DB_USERNAME_OMSL', 'forge'),
            'password' => env('DB_PASSWORD_OMSL', ''),
            'encrypt' => env('DB_ENCRYPT_OMSL', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE_OMSL', 'no'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'prd' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_PRD', '172.16.0.199'),
            'port' => env('DB_PORT_PRD', '1433'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_PRD', 'forge'),
            'username' => env('DB_USERNAME_PRD', 'forge'),
            'password' => env('DB_PASSWORD_PRD', ''),
            'encrypt' => env('DB_ENCRYPT_PRD', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE_PRD', 'no'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'PRD199' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST_PRD199', '172.16.0.199'),
            'port' => env('DB_PORT_PRD199', '1433'),
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE_PRD199', 'forge'),
            'username' => env('DB_USERNAME_PRD199', 'forge'),
            'password' => env('DB_PASSWORD_PRD199', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
        

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];

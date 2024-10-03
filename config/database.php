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
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
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
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
            'encrypt' => 'no',
        ],

        'KRON_CI' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_CI_HOST'),
            'port' => env('DB_CI_PORT'),
            'database' => env('DB_CI_DATABASE'),
            'username' => env('DB_CI_USERNAME'),
            'password' => env('DB_CI_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],

        'KRON_CI_TEST' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_CI_TEST_HOST'),
            'port' => env('DB_CI_TEST_PORT'),
            'database' => env('DB_CI_TEST_DATABASE'),
            'username' => env('DB_CI_TEST_USERNAME'),
            'password' => env('DB_CI_TEST_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],

        'SNAPSHOT' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_SNAPSHOT_HOST'),
            'port' => env('DB_SNAPSHOT_PORT'),
            'database' => env('DB_SNAPSHOT_DATABASE'),
            'username' => env('DB_SNAPSHOT_USERNAME'),
            'password' => env('DB_SNAPSHOT_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_BULGAR' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_BULGAR_HOST'),
            'port' => env('DB_KRON4_BULGAR_PORT'),
            'database' => env('DB_KRON4_BULGAR_DATABASE'),
            'username' => env('DB_KRON4_BULGAR_USERNAME'),
            'password' => env('DB_KRON4_BULGAR_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_TRN' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_TRN_HOST'),
            'port' => env('DB_KRON4_TRN_PORT'),
            'database' => env('DB_KRON4_TRN_DATABASE'),
            'username' => env('DB_KRON4_TRN_USERNAME'),
            'password' => env('DB_KRON4_TRN_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_YAO' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_YAO_HOST'),
            'port' => env('DB_KRON4_YAO_PORT'),
            'database' => env('DB_KRON4_YAO_DATABASE'),
            'username' => env('DB_KRON4_YAO_USERNAME'),
            'password' => env('DB_KRON4_YAO_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_OO' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_OO_HOST'),
            'port' => env('DB_KRON4_OO_PORT'),
            'database' => env('DB_KRON4_OO_DATABASE'),
            'username' => env('DB_KRON4_OO_USERNAME'),
            'password' => env('DB_KRON4_OO_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_ALOIL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_ALOIL_HOST'),
            'port' => env('DB_KRON4_ALOIL_PORT'),
            'database' => env('DB_KRON4_ALOIL_DATABASE'),
            'username' => env('DB_KRON4_ALOIL_USERNAME'),
            'password' => env('DB_KRON4_ALOIL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_MNKT' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_MNKT_HOST'),
            'port' => env('DB_KRON4_MNKT_PORT'),
            'database' => env('DB_KRON4_MNKT_DATABASE'),
            'username' => env('DB_KRON4_MNKT_USERNAME'),
            'password' => env('DB_KRON4_MNKT_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_SHOIL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_SHOIL_HOST'),
            'port' => env('DB_KRON4_SHOIL_PORT'),
            'database' => env('DB_KRON4_SHOIL_DATABASE'),
            'username' => env('DB_KRON4_SHOIL_USERNAME'),
            'password' => env('DB_KRON4_SHOIL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON4_CC' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON4_CC_HOST'),
            'port' => env('DB_KRON4_CC_PORT'),
            'database' => env('DB_KRON4_CC_DATABASE'),
            'username' => env('DB_KRON4_CC_USERNAME'),
            'password' => env('DB_KRON4_CC_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'MNKT_STAGE' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_MNKT_STAGE_HOST'),
            'port' => env('DB_MNKT_STAGE_PORT'),
            'database' => env('DB_MNKT_STAGE_DATABASE'),
            'username' => env('DB_MNKT_STAGE_USERNAME'),
            'password' => env('DB_MNKT_STAGE_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON_DEV' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON_DEV_HOST'),
            'port' => env('DB_KRON_DEV_PORT'),
            'database' => env('DB_KRON_DEV_DATABASE'),
            'username' => env('DB_KRON_DEV_USERNAME'),
            'password' => env('DB_KRON_DEV_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON_REF' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON_REF_HOST'),
            'port' => env('DB_KRON_REF_PORT'),
            'database' => env('DB_KRON_REF_DATABASE'),
            'username' => env('DB_KRON_REF_USERNAME'),
            'password' => env('DB_KRON_REF_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON_TM_DEV' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON_TM_DEV_HOST'),
            'port' => env('DB_KRON_TM_DEV_PORT'),
            'database' => env('DB_KRON_TM_DEV_DATABASE'),
            'username' => env('DB_KRON_TM_DEV_USERNAME'),
            'password' => env('DB_KRON_TM_DEV_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'KRON_TM_REF' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_KRON_TM_REF_HOST'),
            'port' => env('DB_KRON_TM_REF_PORT'),
            'database' => env('DB_KRON_TM_REF_DATABASE'),
            'username' => env('DB_KRON_TM_REF_USERNAME'),
            'password' => env('DB_KRON_TM_REF_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_SHESHMAOYL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_SHESHMAOYL_HOST'),
            'port' => env('DB_TM_SHESHMAOYL_PORT'),
            'database' => env('DB_TM_SHESHMAOYL_DATABASE'),
            'username' => env('DB_TM_SHESHMAOYL_USERNAME'),
            'password' => env('DB_TM_SHESHMAOYL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_GEOLOGIYA_1' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_GEOLOGIYA_1_HOST'),
            'port' => env('DB_TM_GEOLOGIYA_1_PORT'),
            'database' => env('DB_TM_GEOLOGIYA_1_DATABASE'),
            'username' => env('DB_TM_GEOLOGIYA_1_USERNAME'),
            'password' => env('DB_TM_GEOLOGIYA_1_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_GEOLOGIYA_2' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_GEOLOGIYA_2_HOST'),
            'port' => env('DB_TM_GEOLOGIYA_2_PORT'),
            'database' => env('DB_TM_GEOLOGIYA_2_DATABASE'),
            'username' => env('DB_TM_GEOLOGIYA_2_USERNAME'),
            'password' => env('DB_TM_GEOLOGIYA_2_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_GEOTEHZAR' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_GEOTEHZAR_HOST'),
            'port' => env('DB_TM_GEOTEHZAR_PORT'),
            'database' => env('DB_TM_GEOTEHZAR_DATABASE'),
            'username' => env('DB_TM_GEOTEHZAR_USERNAME'),
            'password' => env('DB_TM_GEOTEHZAR_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_GEOTEHGLAZ' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_GEOTEHGLAZ_HOST'),
            'port' => env('DB_TM_GEOTEHGLAZ_PORT'),
            'database' => env('DB_TM_GEOTEHGLAZ_DATABASE'),
            'username' => env('DB_TM_GEOTEHGLAZ_USERNAME'),
            'password' => env('DB_TM_GEOTEHGLAZ_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_NK_GEOLOGIYA' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_NK_GEOLOGIYA_HOST'),
            'port' => env('DB_TM_NK_GEOLOGIYA_PORT'),
            'database' => env('DB_TM_NK_GEOLOGIYA_DATABASE'),
            'username' => env('DB_TM_NK_GEOLOGIYA_USERNAME'),
            'password' => env('DB_TM_NK_GEOLOGIYA_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_KONDURCHANEFT' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_KONDURCHANEFT_HOST'),
            'port' => env('DB_TM_KONDURCHANEFT_PORT'),
            'database' => env('DB_TM_KONDURCHANEFT_DATABASE'),
            'username' => env('DB_TM_KONDURCHANEFT_USERNAME'),
            'password' => env('DB_TM_KONDURCHANEFT_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_IDELOYL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_IDELOYL_HOST'),
            'port' => env('DB_TM_IDELOYL_PORT'),
            'database' => env('DB_TM_IDELOYL_DATABASE'),
            'username' => env('DB_TM_IDELOYL_USERNAME'),
            'password' => env('DB_TM_IDELOYL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_ELABUGANEFT' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_ELABUGANEFT_HOST'),
            'port' => env('DB_TM_ELABUGANEFT_PORT'),
            'database' => env('DB_TM_ELABUGANEFT_DATABASE'),
            'username' => env('DB_TM_ELABUGANEFT_USERNAME'),
            'password' => env('DB_TM_ELABUGANEFT_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_TATNEFTEPROM' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_TATNEFTEPROM_HOST'),
            'port' => env('DB_TM_TATNEFTEPROM_PORT'),
            'database' => env('DB_TM_TATNEFTEPROM_DATABASE'),
            'username' => env('DB_TM_TATNEFTEPROM_USERNAME'),
            'password' => env('DB_TM_TATNEFTEPROM_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_KA_ALMET' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_KA_ALMET_HOST'),
            'port' => env('DB_TM_KA_ALMET_PORT'),
            'database' => env('DB_TM_KA_ALMET_DATABASE'),
            'username' => env('DB_TM_KA_ALMET_USERNAME'),
            'password' => env('DB_TM_KA_ALMET_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_KA_AKAN' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_KA_AKAN_HOST'),
            'port' => env('DB_TM_KA_AKAN_PORT'),
            'database' => env('DB_TM_KA_AKAN_DATABASE'),
            'username' => env('DB_TM_KA_AKAN_USERNAME'),
            'password' => env('DB_TM_KA_AKAN_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_KA_BUREYKA' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_KA_BUREYKA_HOST'),
            'port' => env('DB_TM_KA_BUREYKA_PORT'),
            'database' => env('DB_TM_KA_BUREYKA_DATABASE'),
            'username' => env('DB_TM_KA_BUREYKA_USERNAME'),
            'password' => env('DB_TM_KA_BUREYKA_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_KARBONOYL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_KARBONOYL_HOST'),
            'port' => env('DB_TM_KARBONOYL_PORT'),
            'database' => env('DB_TM_KARBONOYL_DATABASE'),
            'username' => env('DB_TM_KARBONOYL_USERNAME'),
            'password' => env('DB_TM_KARBONOYL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
        ],
        'TM_BLAGODAROVOYL' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_TM_BLAGODAROVOYL_HOST'),
            'port' => env('DB_TM_BLAGODAROVOYL_PORT'),
            'database' => env('DB_TM_BLAGODAROVOYL_DATABASE'),
            'username' => env('DB_TM_BLAGODAROVOYL_USERNAME'),
            'password' => env('DB_TM_BLAGODAROVOYL_PASSWORD'),
            'options' => [
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::SQLSRV_ATTR_FETCHES_NUMERIC_TYPE => true,
            ],
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

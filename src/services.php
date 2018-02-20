<?php


use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

/**
 * @var \Silex\Application $app
 */

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => [
        'default' => [
            'driver'   => 'pdo_mysql',
            'host'     => getenv('DB_HOST'),
            'dbname'   => getenv('DB_NAME'),
            'user'     => getenv('DB_USER'),
            'password' => file_get_contents('/run/secrets/DB_PASS'),
        ],
    ],
));


$app->register(new DoctrineOrmServiceProvider, [
    'orm.proxies_dir' => '/path/to/proxies',
    'orm.em.options'  => [
        'mappings' => [
            [
                'type'                         => 'annotation',
                'namespace'                    => 'Entity',
                'path'                         => __DIR__,
                'use_simple_annotation_reader' => false,
            ],
        ],
    ],
]);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/Resources/views',
));

$app->register(new Silex\Provider\SessionServiceProvider());
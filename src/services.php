<?php


use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Form\FormRenderer;

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
    'orm.proxies_dir' => __DIR__ . '/../var/cache/doctrine/proxies',
    'orm.em.options'  => [
        'mappings' => [
            [
                'type'                         => 'annotation',
                'namespace'                    => 'Entity',
                'path'                         => __DIR__ . '/Entity',
                'use_simple_annotation_reader' => false,
            ],
        ],
    ],
]);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/Resources/views',
));

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains'          => array(),
    'translator.message_selector' => null,
));
$app['locale'] = 'en';

$app->extend('twig.runtimes', function ($runtimes, $app) {
    return array_merge($runtimes, [
        FormRenderer::class => 'twig.form.renderer',
    ]);
});
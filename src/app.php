<?php

use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;



$app = new Application();

$app->register(new FormServiceProvider());

$app->register(new TranslationServiceProvider());

$app->register(new MonologServiceProvider(), [
    'monolog.logfile' => __DIR__.'/dev.log',
]);

$app->register(new UrlGeneratorServiceProvider());

$app->register(new SessionServiceProvider());


$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__.'/../web/views',
]);

$app->register(new ValidatorServiceProvider());

$app->before(function () use ($app) {
    $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.html.twig'));
});


$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    return $twig;
}));


return $app;




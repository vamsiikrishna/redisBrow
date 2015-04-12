<?php

use Symfony\Component\Validator\Constraints as Assert;
use RedisBrow\Helper\RedisHelper;

$app->get('/', function () use ($app) {
    $client = new RedisHelper('127.0.0.1',6379);
    $info = $client->info();
    $dbsize = $client->dbsize();
    return $app['twig']->render('home.html.twig',array('info'=>$info,'dbsize'=>$dbsize));

})->bind('home');
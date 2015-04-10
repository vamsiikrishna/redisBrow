<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use RedisBrow\Helper\RedisHelper;

$app->get('/', function () use ($app) {

    $client = new RedisHelper('127.0.0.1',1334);
    $info = $client->info();
    $dbsize = $client->dbsize();
    return $app['twig']->render('home.html.twig',array('info'=>$info,'dbsize'=>$dbsize));

})->bind('home');
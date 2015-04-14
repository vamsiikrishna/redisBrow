<?php

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use RedisBrow\Helper\RedisHelper;

$app->get('/info', function () use ($app) {

    $server = $app['session']->get('server');
    $host = $server['host'];
    $port = $server['port'];
    $client = new RedisHelper($host, $port);
    $info = $client->info();
    $dbsize = $client->dbsize();
    return $app['twig']->render('info.html.twig', array('info' => $info, 'dbsize' => $dbsize));

})->bind('info');

$app->match('/', function (Request $request) use ($app) {

    $data = array(
        'host' => '127.0.0.1',
        'port' => '6379',
    );
    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('host', 'text', array('attr' => array('class' => 'form-control')))
        ->add('port', 'text', array('attr' => array('class' => 'form-control')))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        $host = $data['host'];
        $port = $data['port'];
        $app['session']->set('server', array('host' => $host, 'port' => $port));
        return $app->redirect('/info');
    }
    return $app['twig']->render('home.html.twig', array('form' => $form->createView()));

})->bind('home');
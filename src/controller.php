<?php

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use RedisBrow\Helper\RedisHelper;

$app->get('/info', function () use ($app) {
	$server = $app['session']->get('server');
	$host = $server['host'];
	$port = $server['port'];
    $client = new RedisHelper($host,$port);
    $info = $client->info();
    $dbsize = $client->dbsize();
    return $app['twig']->render('info.html.twig',array('info'=>$info,'dbsize'=>$dbsize));

})->bind('info');

$app->match('/', function (Request $request) use ($app) {
     // some default data for when the form is displayed the first time
    $data = array(
        'host' => '127.0.0.1',
        'port' => '6379',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('host')
        ->add('port')
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();
        $host = $data['host'];
        $port = $data['port'];
        $app['session']->set('server', array('host' => $host,'port'=>$port));

       // var_dump($data);die();

        return $app->redirect('/info');
    }

    // display the form
    return $app['twig']->render('home.html.twig', array('form' => $form->createView()));


})->bind('home');
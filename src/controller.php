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


$app->get('/keys/{pageid}', function () use ($app) {

    $server = $app['session']->get('server');
    $host = $server['host'];
    $port = $server['port'];
    //$id   = $app['pageid'];
    $pageValueArray = array();
    $pageid = $app['request']->get('pageid');
    $client = new RedisHelper($host, $port);
    $flag = 0;

    //$keys = $client->scan(0);
    
    if($pageid){
        $start = $pageid;
    }
    $end = $start + 5;
    $pageArray = array();
    if($pageid==1){
        $pageArray[$pageid]=0; 
    }else {
         $pageinfo = $app['session']->get('paginfo');
         $pageArray[$pageid] = $pageinfo[$pageid];

         if(!isset($pageArray[$pageid])){
            $flag = 1;
         }
         
    }

    $scanvalue = $pageArray[$pageid];
   // echo "<pre />";
    //print_r ($pageArray);die(); 

    $keys = $client->scan($scanvalue);
    $pageValueArray =  $keys[1];
    $pagination['pageid'] = $pageid;
    $pagination['end']= $end;

    $pagination['flag']= $flag;
   
    

    // pagination code 
    for($itr=$start;$itr<$end;$itr++){
        $scanvalue = $pageArray[$itr];
           $keys = $client->scan($scanvalue);
           $pageArray[$itr+1] = $keys[0];
    }


    $app['session']->set('paginfo',$pageArray);
    //var_dump($pageArray);die();

    
    return $app['twig']->render('keys.html.twig', array('pageValueArray' => $pageValueArray,'pagination'=> $pagination ));

})->bind('keys');


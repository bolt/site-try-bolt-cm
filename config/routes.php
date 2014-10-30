<?php
use Aura\Router\RouterFactory;


$router_factory = new RouterFactory;
$router = $router_factory->newInstance();

/******* Main Page Routes ************/
$router->add("home", "/")->setValues(['action'=>'Bolt\Demo\Action\Home']);
$router->add("check", "/check/{demo}")->setValues(['action'=>'Bolt\Demo\Action\Check']);
$router->add("build", "/build/{demo}")->setValues(['action'=>'Bolt\Demo\Action\Build']);

return $router;

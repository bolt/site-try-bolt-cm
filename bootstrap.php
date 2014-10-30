<?php
$builder = new DI\ContainerBuilder();
$builder->useAnnotations(false);
$builder->addDefinitions(__DIR__."/config/config.php");
$container = $builder->build();

$app = $container->get("app");


$app = (new Stack\Builder())
        ->push('Stack\Session')
        ->push('Stack\Aura\RequestRouter', $container->get('router'))
        ->resolve($app);

Stack\run($app);
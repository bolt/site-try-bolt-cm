<?php
use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrateConfig;
use Doctrine\DBAL\Driver\Connection as DB;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Persistence\Mapping\Driver\StaticPHPDriver;
use Doctrine\DBAL\DriverManager;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Aura\Router\Router;
use DI\ContainerBuilder;

use Bolt\Demo\Application;


Symfony\Component\Debug\Debug::enable();
@include_once 'env.php';
return [

    'debug' => false, 

    Application::class => DI\object(),
    
    'app' => DI\link(Application::class),
    
    'db' => DI\factory(function($c){
        return [
            'driver'     => 'pdo_mysql',
            'dbname'     => 'bolt_demo',
            'host'       => '127.0.0.1',
            'user'       => 'bolt_demo',
            'password'   => getenv('APP_DB_PASSWORD')
        ];
    }),


    DB::class => DI\factory(function($c) {
        return DriverManager::getConnection($c->get('db'));
    }),
    
    EntityManager::class => DI\factory(function($c){
        $driver = new StaticPHPDriver(dirname(__DIR__) . '/src/Entity');
        $config = Setup::createConfiguration(true);
        $config->setMetadataDriverImpl($driver);
        $config->setAutoGenerateProxyClasses($c->get('debug'));
        $em = EntityManager::create($c->get(DB::class), $config);
        return $em;
    }),
    
    "migrations" => DI\factory(function($c){
        $m = new MigrateConfig($c->get(DB::class));
        $m->setMigrationsDirectory(dirname(__DIR__)."/src/Migrations");
        $m->setMigrationsNamespace("Bolt\Extensions");
        $m->registerMigrationsFromDirectory(dirname(__DIR__)."/src/Migrations");
        return $m;
    }),

    
    Router::class => DI\factory(function($c){
        $router = include(__DIR__."/routes.php");
        return $router;
    }),
    
    'router' => DI\link(Router::class),

    
    Twig_Environment::class => DI\factory(function ($c) {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/Templates');
        $twig = new Twig_Environment($loader);
        $formEngine = new TwigRendererEngine(["forms.html"]);
        $formEngine->setEnvironment($twig);
        $twig->addExtension(new Bolt\Demo\Helper\Url($c->get(Router::class)));
        $twig->addExtension(new FormExtension(new TwigRenderer($formEngine)));
        return $twig;
    }),
    
    FormFactory::class => DI\Factory(function($c){
        return Forms::createFormFactoryBuilder()
            ->addType(new Bolt\Demo\Form\DemoForm($c->get('Bolt\Demo\Service\ThemeProvider')))
            ->getFormFactory();
    }),
    
    
    'console.commands' => DI\factory(function($c){
        return [
            $c->get(Bolt\Demo\Command\DemoRunner::class)
        ];
    }),

    
    


];

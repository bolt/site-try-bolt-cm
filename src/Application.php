<?php
namespace Bolt\Demo;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

use DI\Container;

class Application implements HttpKernelInterface {

    public $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }



    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $this->container->set(Request::class, $request);
        $route = $request->attributes->get("route");
        if (isset($route['action']) && class_exists($route['action'])) {
            $action = $this->container->get($route['action']);
            
            if (is_callable($action)) {
                if (method_exists($action, 'setRequest')) {
                    $action->setRequest($request);    
                }
                return $action($request, $route);
            }
        }
    }


}
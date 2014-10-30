<?php
namespace Bolt\Demo\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Twig_Environment;
use Doctrine\ORM\EntityManager;
use Bolt\Demo\Entity;

class Build
{
    public $renderer;
    public $em;
    
    public function __construct(Twig_Environment $renderer, EntityManager $em)
    {
        $this->renderer = $renderer;
        $this->em = $em;
    }
    
    public function __invoke(Request $request, $params)
    {
        $repo = $this->em->getRepository(Entity\Demo::class);
        $demo = $repo->findOneBy(['id'=> $params['demo']]);
        
        return new Response($this->renderer->render('building.html', ['demo'=>$demo]));
    }
    

}
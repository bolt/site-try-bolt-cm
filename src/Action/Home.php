<?php
namespace Bolt\Demo\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig_Environment;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Bolt\Demo\Entity;
use Bolt\Demo\Service\ThemeProvider;
use Aura\Router\Router;

class Home
{
    public $renderer;
    public $forms;
    public $em;
    
    public function __construct(Twig_Environment $renderer, FormFactory $forms, EntityManager $em, ThemeProvider $themes, Router $router)
    {
        $this->renderer = $renderer;
        $this->forms = $forms;
        $this->em = $em;
        $this->themes = $themes;
        $this->router = $router;
    }
    
    public function __invoke(Request $request)
    {
        $entity = new Entity\Demo;
        $form = $this->forms->create('demo', $entity);
        $form->handleRequest();
        if ($form->isValid()) {
           $demo = $form->getData();
           $demo->setStatus('waiting');
           $demo->setCreated(new \DateTime);
           $this->em->persist($demo);
           $this->em->flush();
           return new RedirectResponse($this->router->generate('build', ['demo'=>$demo->getId()])); 
        }
        return new Response($this->renderer->render("home.html", ['form'=>$form->createView(), 'themes'=>$this->themes->getThemes()]));

    }
    

}
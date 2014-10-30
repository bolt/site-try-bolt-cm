<?php
namespace Bolt\Demo\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Bolt\Demo\Service\ThemeProvider;

class DemoForm extends AbstractType
{
    
    public $themes;
    
    public function __construct(ThemeProvider $themes)
    {
        $this->themes = $themes;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("title", 'text', ['label'=>"Give your site a title"])
            ->add("theme", 'choice', ['label'=>"Choose a theme from the previews on your right", 'choices'=>$this->themes->getThemeOptions()])
            ->add('submit', 'submit', ['label'=>"Create Your Bolt Site"]);


    }

    public function getName()
    {
        return 'demo';
    }


}

<?php
namespace Bolt\Demo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\EntityManager;
use Bolt\Demo\Entity;
use Symfony\Component\Process\Process;




class DemoRunner extends Command {
    

    public $em;
    public $isRunning = false;
    public $waitTime = 5;
    public $protocol = "http://";
    
 
    public function __construct(EntityManager $em) {
        $this->em = $em;
        parent::__construct();
    }
    

    protected function configure() {
        $this->setName("bolt:demo-runner")
                ->setDescription("Looks in the queue and launches a test instance of a Bolt with theme loaded.");

    }

    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        while (true) {
            if(false === $this->isRunning) {
                if($demo = $this->checkQueue() ) {
                    $this->startJob($demo, $output);
                }
            }
            $output->writeln("Sleeping for ".$this->waitTime." seconds");
            sleep($this->waitTime);
        }
    }
    
    protected function checkQueue()
    {
        $repo = $this->em->getRepository(Entity\Demo::class);
        $demo = $repo->findOneBy(['status'=>'waiting']);
        return $demo;
    }
    
    protected function startJob($demo, OutputInterface $output)
    {
        $this->isRunning = true;
        $demo->setStatus("building");
        $this->em->flush();
        
        $command = "ssh boltrunner@bolt.rossriley.co.uk 'cap production docker:run package=".$demo->getTheme()." version=\* theme=".$demo->getTheme()." title=".$demo->getTitle()."'";
        
        $process = new Process($command);
        $process->run();
        
        if ($process->isSuccessful()) {
            $response = $process->getOutput();
            $lines = explode("\n", $response);
            if( !isset($lines[5])) {
                // This means the container couldn't launch a new instance.
                // Best bet here is to remain in waiting mode and try again next loop
                return;
            }
            $demo->setStatus("complete");
            $demo->setUrl($this->protocol.$lines[5]);
            $output->writeln("<info>Built ".$demo->getTheme()." to ".$demo->getUrl()."</info>");
            $this->em->flush();
        } else {
            $demo->setStatus("failed");
            $demo->setUrl($lines);
            $this->em->flush();
        }
        $this->isRunning = false;
    }
    


}
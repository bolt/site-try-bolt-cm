<?php

namespace Bolt\Demo\Entity;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

class Demo {

    protected $id;
    protected $title;
    protected $theme;
    protected $status;
    protected $created;
    protected $url;
    
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getTheme()
    {
        return $this->theme;
    }
    
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    
    public static function loadMetadata(ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->createField('id', 'guid')->isPrimaryKey()->generatedValue("UUID")->build();
        $builder->addField('title', 'string',   ['nullable'=>true]);
        $builder->addField('theme', 'string',   ['nullable'=>true]);
        $builder->addField('created', 'datetime', ['nullable'=>true]);
        $builder->addField('status', 'string',   ['nullable'=>true]);
        $builder->addField('url', 'string',   ['nullable'=>true]);
    }


}
<?php
namespace MediaManager\Filter\Tag;

class Media
{
   
   protected $config = array();
   protected $mediaManager;
   protected $renderer;

   public function __construct ($config = array(), $mediaManger, $renderer)
   {
   	  $this->config = $config;
   	  $this->mediaManager = $mediaManager;
   	  $this->renderer = $renderer;   	  
   }

   public function filter($value, $parameters = array())
   {
   	 $args = array_pad($parameters, 12, false);

   	 $bestMatch = $this->mediaManager->bestMatch($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8], $args[9], $args[10], $args[11]);

   	 $mediaValue = $bestMatch[0];
     unsert($bestMatch);
     return $this->renderer->mediaManager($mediaManager, $bestMatch);
     
   }
}
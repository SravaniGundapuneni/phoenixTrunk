<?php
namespace MediaManager\Filter\Tag;

class Upload
{
	protected $config = array();
	protected $uploadManager;
	protected $renderer;

	public function __construct ($config = array(), $uploadManager, $renderer)
	{
		$this->config = $config;
		$this->uploadManager = $uploadManager;
		$this->renderer = $renderer;
    }
	public function filter($value, $paramerters = array())
	{
		$args = array_pad($parameters, 9, false);
		$bestMatch = $this->uploadManager($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);

    	$uploadValue = $bestMatch[0];
		unsert($bestMatch);
		return $this->renderer->uploadManager($mediaManager, $bestMatch);
	}

	
}

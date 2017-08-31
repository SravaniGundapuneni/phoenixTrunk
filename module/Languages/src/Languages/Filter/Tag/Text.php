<?php
namespace Languages\Filter\Tag;

class Text extends \Phoenix\Module\Filter\Tag\Filter
{
    protected $config = array();
    protected $textService;
    protected $renderer;

    /**
     * Setup the filter tag
     *
     * @param array  $config
     * @param Languages\Service\Polytext $textService
     * @param Zend\View\Renderer\PhpRenderer $renderer
     */
    public function __construct($config = array(), $textService, $renderer)
    {
        $this->config = $config;
        $this->textService = $textService;
        $this->renderer = $renderer;
    }

    /**
     * Parses the parameters fro us
     *
     * @param  array $parameters
     * @return array
     */
    protected function parseParameters($parameters)
    {
        $parameters = $this->removeTag($parameters);
        $area       = array_shift($parameters);
        $name       = array_shift($parameters);

        return array($area, $name, $parameters);
    }

    public function filter($value, $parameters = array())
    {
        list($area,$name,$parameters) = $this->parseParameters($parameters);
        $bestMatch = $this->textService->bestMatch($area, $name, $parameters);

        return $this->renderer->polytextElement($bestMatch);
    }
}
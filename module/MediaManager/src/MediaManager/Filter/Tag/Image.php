<?php

namespace MediaManager\Filter\Tag;

class Image extends \Phoenix\Module\Filter\Tag\Filter
{

    protected $config = array();
    protected $imageService;
    protected $renderer;

    /**
     * Setup the filter tag
     *
     * @param array  $config
     * @param MediaManager\Service\MediaManagerImages $textService
     * @param Zend\View\Renderer\PhpRenderer $renderer
     */
    public function __construct($config = array(), $imageService, $renderer)
    {
        $this->config = $config;
        $this->imageService = $imageService;
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

        $mediaRoot = '/d/';

        $imgPath = SITE_PATH . $mediaRoot . $parameters['src'];
        if (file_exists($imgPath)) {
            $image = $this->imageService->getImageByName($parameters['src']);
            $parameters['alt'] = $image->getAltText();
            $parameters['addThisTitle'] = '';
            $parameters['addThisUrl'] = '';
            $parameters['default'] = false;
        } else {
            $parameters['src'] = $this->config->get(array("paths", "toolboxIncludeUrl")) . "module/Toolbox/view/layout/img/default.png";
            $parameters['alt'] = '';
            $parameters['addThisTitle'] = '';
            $parameters['addThisUrl'] = '';
            $parameters['default'] = true;
        }

        return array($parameters['src'], $parameters);
    }

    public function filter($value, $parameters = array())
    {

        list($image, $parameters) = $this->parseParameters($parameters);
        $bestMatch = $this->imageService->bestMatch($image, $parameters);

        return $this->renderer->imageElement($bestMatch);
    }

}

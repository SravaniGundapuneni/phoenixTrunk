<?php

namespace MediaManager\Helper;

use Zend\View\Helper\AbstractHelper;

class GetImageElement extends AbstractHelper
{

    protected $sm;

    public function __construct($sm)
    {
        $this->sm = $sm;
    }

    public function __invoke($imageSource, $parameters)
    {
        $serviceLocator = $this->sm->getServiceLocator();
        $mediaManagerService = $serviceLocator->get('phoenix-mediamanager');

        $mediaItem = $mediaManagerService->getItemBy(array('name' => 'test.txt'));

        /**
         * Build the element attributes
         */
        $attributes = Array();
        foreach ($parameters as $key => $value) {
            $attributes[$key] = " {$key}=\"{$value}\"";
        }

        $this->getView()->imageValue = $imageSource;
        $this->getView()->attributes = implode($attributes);

        return $this->getView()->render('media-manager/helpers/media-image-element');
    }

}

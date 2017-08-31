<?php
namespace PhoenixSocialToolbar\Helper;

use Zend\View\Helper\AbstractHelper;

class GetSocialToolbarElement extends AbstractHelper
{
    protected $socialToolbar;

    public function __invoke()
    {      
        $result = array();
        $social = $this->socialToolbar->getItems();

        foreach ($social as $key => $item)
        {
            $socialArray = $item->toArray();
            $result[] = $socialArray;
        }
 
        if (empty($result)) {
            return array();
        }

        $this->getView()->imgTwitter      = $result[0]['imgTwitter'];
        $this->getView()->imgGoogle       = $result[0]['imgGoogle'];
        $this->getView()->imgFacebook     = $result[0]['imgFacebook'];   
        $this->getView()->smFacebook      = $result[0]['smFacebook'];
        $this->getView()->smTwitter       = $result[0]['smTwitter'];
        $this->getView()->toolbarEnabled  = $result[0]['toolbarEnabled'];
        $this->getView()->layout          = $result[0]['layout'];

        return $this->getview()->render('social-toolbar-item');

       
    }

    public function __construct($socialToolbar)
    {
        $this->socialToolbar = $socialToolbar;
    }
}
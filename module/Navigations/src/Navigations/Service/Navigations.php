<?php

/**
 * The Navigations Service
 *
 * @category    Toolbox
 * @package     Navigations
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Navigations\Service;

use Navigations\Model\Navigation;
use Pages\EventManager\Event as PagesEvent;
use ListModule\Service\UnifiedLists;

class Navigations extends UnifiedLists
{

    public $_action = ''; //to identify user action for filtering items  

    /**
     * __construct
     *
     * Construct our Navigations service
     *
     * @return void
     */

    public function __construct()
    {

        $this->entityName = Navigation::NAVIGATION_ENTITY_NAME;
        $this->modelClass = "\Navigations\Model\Navigation";
        $this->dataItem = Navigation::NAVIGATION_DATA_ENTITY;
    }

    public function getPropertyIdOptions()
    {

        $options = array();
        //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }

        if ($this->currentUser->getIsCorporate()) {
            $property = new \Zend\Form\Element\Hidden('propertyId');
            $property->setValue('Null');
        } else {

            $property = new \Zend\Form\Element\Select('propertyId');
            $property->setLabel('Hotel');
            $property->setLabelAttributes(array('class' => 'blockLabel'));
            $property->setAttribute('class', 'stdTextInput');
            $property->setValueOptions($options);
        }

        return $options;
    }

    public function getParentOptions()
    {

        $options = array();
        //inject default property as Not Assigned
        $options[0] = 'Not Assigned';

        $hotels = $this->getItemsBy(array('status' => 1, 'parent' => 0, 'navCategory' => 'mainmenu'));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getNavigationName();
        }


        return $options;
    }

   public function getWidgetData($menu)
    {
        $languageTranslations = $this->getDefaultEntityManager()
                        ->getRepository('Navigations\Entity\Navigations')->findBy(array('status' => 1, 'parent' => 0, 'navCategory' => $menu));
        $arrayFooter = array();
        foreach ($languageTranslations as $index => $value) {
            $arraySubMenu = array();
            $navigationId = $value->getId();
            $languageTranslationsMenu = $this->getDefaultEntityManager()
                            ->getRepository('Navigations\Entity\Navigations')->findBy(array('status' => 1, 'parent' => $navigationId));
            foreach ($languageTranslationsMenu as $indexMenu => $valueMenu) {

                $arraySubMenu[$valueMenu->getNavigationOrder()] = array(
                    'key' => $valueMenu->getNavigationKey(),
                    'name' => $valueMenu->getNavigationName(),
                    'url' => $valueMenu->getNavigationUrl(),
                    'externalLink' => ""
                );
            }
            $arrayFooter[$value->getNavigationOrder()] = array(
                'key' => $value->getNavigationKey(),
                'name' => $value->getNavigationName(),
                'url' => $value->getNavigationUrl(),
                'externalLink' => $value->getExternalLink(),
                'submenu' => ''
            );
        }
        $arrayMenu = array($menu => $arrayFooter);
        return $arrayMenu;
    }
}

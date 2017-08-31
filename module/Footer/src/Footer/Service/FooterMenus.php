<?php

/**
 * The Footer Service
 *
 * @category    Toolbox
 * @package     Footer
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace Footer\Service;

use Footer\Model\FooterMenu;
use Pages\EventManager\Event as PagesEvent;
use ListModule\Service\UnifiedLists;

class FooterMenus extends UnifiedLists
{

    public $_action = ''; //to identify user action for filtering items  

    /**
     * __construct
     *
     * Construct our Footer service
     *
     * @return void
     */

    public function __construct()
    {

        $this->entityName = FooterMenu::FOOTERMENU_ENTITY_NAME;
        $this->modelClass = "\Footer\Model\FooterMenu";
    }

//    public function getForm($formName, $serviceManager)
//    {
//        echo "getting form from Footer Service getForm()<br/>";
//        $form = parent::getForm($formName, $serviceManager);
//        echo "got form from Footer Service getForm()<br/>";
//        return $form;
//    }
    /**
     * getPropertyIdOptions
     *
     * @todo  Remove this from this service class, so the PhoenixPropeties module will be properly decoupled from Pages.
     * 
     * @return array
     */
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

    public function getFooterWidgetData()
    {
        $languageTranslations = $this->getDefaultEntityManager()
                        ->getRepository('Footer\Entity\Footer')->findBy(array('status' => 1));
        $arrayFooter = array();
        foreach ($languageTranslations as $index => $value) {

            $arrayFooter[$value->getFooterOrder()] = array(
                'key' => $value->getFooterKey(),
                'name' => $value->getFooterName(),
                'url' => $value->getFooterUrl(),
            );
        }

        return $arrayFooter;
    }

}

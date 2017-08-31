<?php
/**
 * Site Model File
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      Sravani gundapuneni <sgundapuneni@travelclick.com>
 * @filesource
 */

namespace PhoenixSite\Model;

use Zend\Stdlib\ArrayObject;

use \ListModule\Model\ListItem;

use ListModule\Model\UnifiedListItem;

/**
 * Site Model
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class SiteComponent extends UnifiedListItem
{
   /**
     * The name of the entity associated with this model
     */
    const ENTITY_NAME = '\PhoenixSite\Entity\PhoenixSiteItems';

    /**
     * The name of the entity class associated with this model
     *
     * So, yeah, this is just a property version of the above constant. The reason for this so we keep
     * the functionality of the constant, while having the property available to be used for dynamically
     * loading the entity class, which for some reason you can't use constant values to do that.
     * 
     * @var string
     */
    protected $entityClass = self::ENTITY_NAME;

    const SITE_DATA_ENTITY = 'phoenixSiteData';
    protected $dataEntityClass = 'PhoenixSite\Entity\PhoenixSiteComponents';


    public function __construct($config = array(), $fields = array()) 
    {
        $this->entityClass = self::ENTITY_NAME;
        $this->dataEntity = self::SITE_DATA_ENTITY;        
        parent::__construct($config, $fields);
       // var_dump($fields);
    }

    // public function __construct($config = array(), $fields = array()) 
    // {
    //     parent::construct($config, $fields);

    //     if ($this->getFields() == array()) {
    //         $newField = new \Toolbox\Entity\ComponentFields();
    //         $newField->setName('label');
    //         $newField->setTranslate(1);

    //         $this->setFields(array($newField));
    //     }
    // }

    protected function checkField($translationField, $modelField) 
    {
        return true;
    }
}


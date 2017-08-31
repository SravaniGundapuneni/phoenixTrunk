<?php
/**
 * The PhoenixAttributes Service
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */
namespace PhoenixAttributes\Service;

use PhoenixAttributes\Model\Attribute;
use PhoenixAttributes\Entity\PhoenixAttribute;

class Attributes extends \ListModule\Service\Lists
{
    /**
     * __construct
     *
     * Construct our attributes service
     *
     * @return void
     */
    public function __construct()
    {
        $this->entityName = Attribute::ENTITY_NAME;
        $this->modelClass = "\PhoenixAttributes\Model\Attribute";
    }

    /**
     * This method is called from the parent class and adds ordering by 'featured' field to the query
     * @param type $qb
     */
    protected function modifyQuery(&$qb)
    {
        $qb->orderBy('pp.featured DESC, pp.property ASC, pp.name ASC');
    }

    /**
     * getAttribute
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \PhoenixAttributes\Model\Attribute
     */
    public function getAttribute($selector)
    {
        $result = null;

        if ( is_numeric($selector) && intval($selector) )
        {
            $result = $this->getItem($selector);
        }
        elseif ( is_string($selector) && strlen($selector))
        {
            $result = $this->getItemBy(array('code' => $selector));
        }
        elseif ( is_array($selector) && $selector )
        {
            $result = $this->getItemBy($selector);
        }

        return $result;
    }

    public function createAttribute($data, $save = false)
    {
        $status = Attribute::DEFAULT_ITEM_STATUS;
        $entityModel = $this->createModel();
        $entityModel->setEntity(new PhoenixAttribute);
        $entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save) $entityModel->save();
        return $entityModel;
    }
}
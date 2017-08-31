<?php
/**
 * Attribute Model
 *
 * @category    Toolbox
 * @package     PhoenixAttributes
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Grou <jrubio@travelclick.com>
 * @filesource
 */
namespace PhoenixAttributes\Model;

class Attribute extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'PhoenixAttributes\Entity\PhoenixAttribute';

    public function __construct()
    {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct();
    }

    /**
     * Pass in the extra data to forms
     * @param  boolean $datesToString
     * @return array
     */
    public function getArrayCopy($datesToString = false)
    {
        $arrayCopy = parent::getArrayCopy($datesToString);
        $arrayCopy['hotelName'] = $this->getProperty()->getName();
        $arrayCopy['isCorporate'] = $this->isCorporate(true);
        return $arrayCopy;
    }

    public function isCorporate($displayYesNo=false)
    {
        $isCorporate = $this->getProperty()->getIsCorporate();
        if ($displayYesNo) $isCorporate = $this->getYesNo($isCorporate);

        return $isCorporate;
    }
}
<?php
/**
 * SeoMetaTexts Model
 *
 * @category    Toolbox
 * @package     SeoMetaText
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Model;

class Components extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'Toolbox\Entity\Components';

    public function __construct()
    {
        $this->entityClass = self::ENTITY_NAME;
       
        parent::__construct();
        
    }
  
}
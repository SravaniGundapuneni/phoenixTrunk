<?php
/**
 * Property Model
 *
 * @category    Toolbox
 * @package     PhoenixProperties
 * @subpackage  Model
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace IHotelier\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Pages\EventManager\Event as PagesEvent;

class IHotelier extends \ListModule\Model\ListItem
{
    const ENTITY_NAME = 'IHotelier\Entity\IHotelier';

    public function __construct()
    {
        $this->entityClass = self::ENTITY_NAME;
        parent::__construct();
    }
    
    
    // public function getPendingApprovals()
    // {
    //    return $this->defaultEntityManager->getRepository('ContentApproval\Entity\ContentApprovalApprovals')->findBy(array('approved' => 0, 'item' => $this->getId()));
        
    // }
}
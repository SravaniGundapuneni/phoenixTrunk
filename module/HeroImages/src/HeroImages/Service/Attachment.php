<?php
/**
 * The HeroImages Service
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace HeroImages\Service;

use HeroImages\Model\Attachments;
use HeroImages\Entity\PhoenixAttachments;

class Attachment extends \ListModule\Service\Lists
{
    protected $entityName;
    protected $categories;
    protected $modelClass = "HeroImages\Model\Attachments";

    public function __construct()
    {
        $this->entityName = "HeroImages\Entity\Attachments";
    }
    
    public function getModuleName()
    {
        return 'HeroImageAttachments';        
    }

   /**
     * getForm
     * 
     * @param  string $formName
     * @param  mixed $sl
     * @return HeroImages\Form\AttachmentForm
     */
    public function getForm($formName, $sl = null)
    {
        $formName = '\HeroImages\Form\AttachmentForm';

        return parent::getForm($formName, $sl);
    }
    
    public function getItemsBy($filters = array(), $orderBy = array())
    {
        return parent::getItemsBy($filters, array('caption' => 'ASC'));
    }
    
}
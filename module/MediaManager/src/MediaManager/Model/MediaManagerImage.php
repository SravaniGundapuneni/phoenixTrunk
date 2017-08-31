<?php

/**
 * MediaManagerImage Model
 *
 * @category                                 Toolbox
 * @package                                  MediaManagerImage
 * @subpackage                               Model
 * @copyright                                Copyright (c) 2013 Travelclick, Inc (http://travelclick.com)
 * @license                                  All Rights Reserved
 * @version                                  Release 1.14
 * @since                                    File available since release 1.14
 * @author                                   Daniel Yang <dyang@travelclick.com>
 * @filesource           
 */

namespace MediaManager\Model;

class MediaManagerImage extends \ListModule\Model\ListItem
{

    const MEDIAMANAGERIMAGE_ENTITY_NAME = 'MediaManager\Entity\MediaManagerImage';

    //==================================================================================================================
    public function __construct()
    {
        $this->entityClass = self::MEDIAMANAGERIMAGE_ENTITY_NAME;
        parent::__construct();
    }

}

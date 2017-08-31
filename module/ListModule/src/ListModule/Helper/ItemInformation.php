<?php
/**
 * The file for the ListModule ItemInformation Helper
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Helper
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The file for the ListModule ItemInformation Helper
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Helper
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class ItemInformation extends AbstractHelper
{
    protected $itemService;
    protected $categoryService;

    /**
     *  __construct function
     * 
     * @access public
     * @param  mixed $itemService
     *
     */
    public function __construct($itemService, $categoryService = null)
    {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
    }
    
    /**
     *  __invoke function
     * 
     * @access public
     * @param mixed $item
     * @param array $params
     * @return mixed $result
     *
     */
    public function __invoke($item, $params = array())
    {
        $result = $this->getResultTemplate($item->getId());

        return $result;
    }

    /**
     *  getResultTemplate function
     * 
     * @access protected
     * @param mixed $itemId
     * @return array $itemId
     *
     */
    protected function getResultTemplate($itemId)
    {
        return array(
            'id' => $itemId,
            'code' => null,
            'name' => null,
            'information' => array(),
        );
    }

    /**
     *  getMainImage function
     * 
     * @access protected
     * @param mixed $item
     * @return mixed $result
     *
     */
    protected function getMainImage($item)
    {
        $result = null;

        if ( $mediaAttachmetns = $this->getMediaAttachments($item) )
        {
            $result = array_shift( $mediaAttachmetns );
        }

        return $result;
    }

    /**
     *  getMediaAttachments function
     * 
     * @access protected
     * @param mixed $item
     * @return mixed $result
     *
     */
    protected function getMediaAttachments($item)
    {
        $result = array();

        $attachments = $item->getMediaAttachments();
        
        foreach ($attachments as $key => $attachment)
        {
            $result[] = array(
                'large' => $attachment->getFilePath(),
                'thumb' => $attachment->getThumbnail(),
                'title' => $attachment->getTitle(),
                'fileId' => $attachment->getFile(),
            );
        }

        return $result;
    }
}
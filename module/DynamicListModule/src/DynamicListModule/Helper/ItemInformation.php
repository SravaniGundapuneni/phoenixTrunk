<?php
/**
 * The file for the DynamicListModule ItemInformation Helper
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

namespace DynamicListModule\Helper;

use ListModule\Helper\ItemInformation as ListItemInformation;

/**
 * The file for the DynamicListModule ItemInformation Helper
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
class ItemInformation extends ListItemInformation
{
    
        public function __construct($itemService, $categoryService = null, $serviceManager = null)
    {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        $this->serviceManager = $serviceManager;
    }
    
    /**
     * __invoke
     *
     * Return the item's front end friendly array
     * 
     * @param  mixed $item
     * 
     * @return array
     */
    public function __invoke($item)
    {
        $result = parent::__invoke($item);

        $result = array_merge($result, $item->getArrayCopy());

        if (isset($result['property'])) {
            $result['property'] = $this->getView()->getPropertyInformation($result['property']);
        }
        
        $result['attachments'] = $this->getMediaAttachments($item);

        
         $mmImages = $this->serviceManager->get('phoenix-mediamanager-image');
         
        foreach ($result['attachments'] as $key => $attachment)
        {
            $mmImage = $mmImages->getItemBy(array('fileId' => $attachment['fileId']));
            $result['attachments'][$key]['altText']= '';
            if ($mmImage)
            {
                $result['attachments'][$key]['altText'] = $mmImage->getAltText();
            }
            
        }
        
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
            'name' => null,
        );
    }    
}
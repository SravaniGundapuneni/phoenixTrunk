<?php

/**
 * The SocketsController controller file
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Controller;

use Zend\View\Model\JsonModel;

/**
 * The SocketsController controller file
 *
 * @category    Toolbox
 * @package     Config
 * @subpackage  Controller
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

class SocketsController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $module;

    /**
     *  __construct function
     * 
     * @access public
     *
     */

    public function __construct() {
        $class = get_class($this);
        $i = strpos($class, "\\");
        $this->module = str_ireplace('phoenix', '', substr($class, 0, $i));
    }

    //=============================================================================================================

    /**
     *  getallAction function
     * 
     * @access public
     * @return mixed $result
     *
     */

    public function getallAction()
    {
        $service = $this->getServiceLocator()->get("phoenix-$this->module");

        $items = array();

        foreach ($service->getItems() as $val) {
            $items[] = $val->toArray();
        }

        $result = new JsonModel(array(
            'success' => true,
            $this->module => $items,
            ));

        return $result;
    }

    public function getModuleName()
    {
        return $this->module;
    }

    public function reorderitemsAction()
    {
        $service = $this->getItemsService();
        $itemsList = $this->params()->fromPost('itemsList');

        if (is_string($itemsList)) {
            $itemsArray = \Zend\Json\Json::decode($itemsList);
        } else {
            $itemsArray = $itemsList;
        }

        $service->reorderItems($itemsArray);

        $data = array('success' => 1, 'error' => 'The items have been reordered.');

        return new JsonModel($data);        
    }

    public function emptytrashAction()
    {
        $service = $this->getItemsService();

        $service->emptyTrash();

        $data = array('success' => 1, 'error' => 'All trashed items for this module have been deleted.');

        return new JsonModel($data);
    }

    protected function getItemsService()
    {
        return $this->getServiceLocator()->get('phoenix-' . $this->getModuleName());
    }
}
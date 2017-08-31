<?php
/**
 * The file for the FlexibleForms GetModule Helper
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

namespace FlexibleForms\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The file for the FlexibleForms GetModule Helper
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
class GetForm extends AbstractHelper
{
    /**
     * $formService
     * 
     * @var FlexibleForms\Service\FlexibleForms
     */
    protected $formService;

    /**
     * $formManager
     * 
     * @var FlexibleForms\Service\DynamicManager
     */
    protected $formManager;

    /**
     * $formName
     * @var string
     */
    protected $formName;

    /**
     * __construct
     * 
     * @param FlexibleForms\Service\FlexibleForms $formService
     * @param FlexibleForms\Service\FlexibleForms $formManager
     */
    public function __construct($formService, $formManager)
    {
        $this->formService = $formService;
        $this->formManager = $formManager;
    }

    /**
     * __invoke
     *
     * Return the item's front end friendly array
     * 
     * @param  mixed $item
     * 
     * @return array|boolean
     */
     public function __invoke($name, $template = '', $action = false)
     {
         if (!$template) {
             $template = 'flexible-forms/helper/get-form';
         }
         $this->formName = $name;
         //echo  $this->formName;
         $this->formManager->setformName($this->formName);        

         $flexibleForm = $this->formService->getFields(array('name' => $this->formName));
         $this->formManager->setFlexibleForm($flexibleForm);

         $ourForm = $this->formManager->getForm();

         if ($action) {
             $ourForm->setAttribute('action', $action);
         }

         $this->getView()->flexibleForm = $ourForm;
//echo 'sgs';
         echo $this->getView()->render($template);
     }
}
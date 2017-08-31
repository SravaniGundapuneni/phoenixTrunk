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
class GetUser extends AbstractHelper
{

    protected $viewFormService;
 /**
     * __construct
     * 
     * @param FlexibleForms\Service\FlexibleForms $formService
     * @param FlexibleForms\Service\FlexibleForms $formManager
     */
    public function __construct($viewFormService)
    {
        $this->viewFormService = $viewFormService;
       // $this->formManager = $formManager;
    }
    public function __invoke() {
        
        $userValue = $this->viewFormService->userProperty();
     // var_dump($userValue);
    return $userValue;
}
   
   
}
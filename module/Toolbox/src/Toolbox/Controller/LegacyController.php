<?php
/**
 * Legacy Conntroller for the Phoenix Application
 *
 * This controller handles all legacy calls for Phoenix. It does all the necessary setup and processing
 * for the legacy operations.
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Controllers
 * @copyright   Copyright (c) 2013 TravelClick
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew Tate <atate@travelclick.com>
 * @filesource
 */



namespace Toolbox\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LegacyController extends AbstractActionController
{
    /**
     * indexAction
     * 
     * The default action for the legacy controller.
     * 
     * @return void
     */
    public function indexAction()
    {
        chdir(SITE_PATH);

        $main = \Toolbox\Legacy\MainLoader::getInstance();
        $paths = $main->getPaths();

        require_once $paths['condorBaseDir'] . 'condorLegacy.php';

        die;
    }
}

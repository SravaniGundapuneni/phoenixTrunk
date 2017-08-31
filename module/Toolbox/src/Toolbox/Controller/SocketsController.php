<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Toolbox\Controller;

use Toolbox\Mvc\Controller\AbstractToolboxController;

use Zend\View\Model\ViewModel;

class SocketsController extends AbstractToolboxController
{
    public function indexAction()
    {
        $data = array();

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        $socket = $this->params()->fromRoute('socket', false);

        /**
         * Make sure to support socket subpath structures
         */
        $socket_name = basename($socket,'.php');
        $socket_subpath = (($path=dirname($socket))!='.')?$path:null;
        $socket_subpath = "{$socket_subpath}/socket.{$socket_name}.php";
        $socket_subpath = str_replace('//','/',"/sockets/{$socket_subpath}");

        $viewManager = $this->getServiceLocator()->get('view-manager');
        $viewModel->setVariables($viewManager->getViewModel()->getVariables());
        $viewModel->setVariable('socket_file_path', SITE_PATH . $socket_subpath);

        return $viewModel;
    }
}
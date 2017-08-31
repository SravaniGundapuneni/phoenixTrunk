<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * The index action. This is the default action for the controller.
     * Right now, it's just being used for debug output.
     * @return \Zend\View\Model\ViewModel $viewModel;
     */
    public function indexAction()
    {
        $em = $this->getServiceLocator()
                 ->get('doctrine.entitymanager.orm_admin');
        $result = $em->getRepository('Users\Entity\Admin\Users')->findBy(array('id' => 7));

        $viewModel = new ViewModel();
        $viewModel->params = $this->params()->fromRoute();
        $viewModel->result = $result;

        $errorHandling = $this->getServiceLocator()->get('phoenix-errorhandler');
        $viewModel->errors = $errorHandling->getErrors();
        return $viewModel;
    }

    public function logoutAction()
    {
        $subsite = $this->params()->fromRoute('subsite', '');

        $redirectRoute = 'home/toolbox-root';
        $redirectTokens = array();

        if ($subsite) {
            $redirectRoute = 'root-subsite';
            $redirectTokens['subsite'] = substr($subsite, 1);
        }

        return $this->redirect()->toRoute($redirectRoute, $redirectTokens);
    }
}
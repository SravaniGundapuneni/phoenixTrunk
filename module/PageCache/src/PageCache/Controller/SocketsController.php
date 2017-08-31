<?php
namespace PageCache\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class SocketsController extends AbstractActionController
{
    public function refreshAction()
    {
        $serviceManager = $this->getServiceLocator();
        $currentUser = $serviceManager->get('phoenix-users-current');

        if (!$currentUser->getUserEntity()) {
            return $this->redirect()->toRoute('home');
        }

        $acl = $serviceManager->get('phoenix-users-acl');

        if (!$acl->isUserAllowed($currentUser, null, 'canAdmin')) {
            $this->flashMessenger()->addErrorMessage('User is not allowed to refresh cache');
        }

        $cacheService = $serviceManager->get('phoenix-pagecache');



        $cacheService->refreshCache();

        $this->flashMessenger()->addSuccessMessage('The cache has been refreshed.');

        $subsite = $this->params()->fromRoute('subsite', '');

        if ($subsite && $subsite != '/' && $subsite != '/sockets') {
            $subsiteRoute = substr($subsite, 1);
            return $this->redirect()->toRoute('home/toolbox-root-subsite', array('subsite' => $subsiteRoute));
        }

        return $this->redirect()->toRoute('home/toolbox-root');
    }
}
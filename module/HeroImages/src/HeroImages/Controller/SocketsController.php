<?php
namespace HeroImages\Controller;

class SocketsController extends \ListModule\Controller\SocketsController
{
    public function getModuleName()
    {
        return 'phoenix-heroimageAttachments';        
    }

    protected function getItemsService()
    {
        $action = $this->params()->fromRoute('action', 'index');

        if ($action == 'emptyTrash') {
            return $this->getServiceLocator()->get('phoenix-heroimages');
        }

        return parent::getItemsService();
    }    
}
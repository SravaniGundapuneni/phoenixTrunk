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

namespace DynamicListModule\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

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

class SocketsController extends BaseSockets
{
    protected function getItemsService()
    {
        $moduleName = $this->params()->fromRoute('module', '');

        if (empty($moduleName)) {
            $service = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');
        } else {
            $service = $this->getServiceLocator()->get('phoenix-dynamicmanager');
            $service->setModuleName($moduleName);
        }

        return $service;
    }

    public function pushmoduleAction()
    {
        $service = $this->getServiceLocator()->get('phoenix-dynamiclistmodule');

        $moduleId = (int) $this->params()->fromRoute('itemId', 0);

        if (empty($moduleId)) {
            $this->flashMessenger()->addErrorMessage('Invalid Module Id Given for Push');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));
        }

        $module = $service->getItem($moduleId);

        if (empty($module)) {
            $this->flashMessenger()->addErrorMessage('Invalid Module Id Given for Push');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));            
        }

        $adminRepoId = $module->getAdminRepoId();
        if (!empty($adminRepoId)) {
            //Eventually we'll use this to update/sync the admin DLM repo with modifications, but that is in the future.
            //For now, we'll just trigger a notice
            $this->flashMessenger()->addInfoMessage('Module already Exists in Admin');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));                        
        } else {
            $adminModule = new \Toolbox\Entity\Admin\Components();
            $adminModule->setName($module->getName());
            $adminModule->setLabel($module->getLabel());
            $adminModule->setDescription($module->getDescription());
            $adminModule->setDynamic(1);
            $adminModule->setCategories($module->getCategories());
            $adminModule->setCreatedUserId($service->getCurrentUser()->getId());
            $adminModule->setModifiedUserId($service->getCurrentUser()->getId());
            $adminModule->setCreated(new \DateTime());
            $adminModule->setModified(new \DateTime());
            $adminModule->setStatus(2);
            $adminModule->setCasEnabled($module->getCasEnabled());
            $adminModule->setCaseAllowed($module->getCasAllowed());

            $service->getAdminEntityManager()->persist($adminModule);
            $service->getAdminEntityManager()->flush();

            $module->getEntity()->setAdminRepoId($adminModule->getId());

            $service->getDefaultEntityManager()->persist($module->getEntity());
            $service->getDefaultEntityManager()->flush();

            $fields = $module->getComponentFields();

            foreach ($fields as $valField) {
                $adminField = new \Toolbox\Entity\Admin\ComponentFields();
                $adminField->setComponent($adminModule);
                $adminField->setName($valField->getName());
                $adminField->setLabel($valField->getLabel());
                $adminField->setTranslate($valField->getTranslate());
                $adminField->setType($valField->getType());
                $adminField->setShowInList($valField->getShowInList());
                $adminField->setOrderNumber($valField->getOrderNumber());
                $adminField->setCreatedUserId($adminModule->getCreatedUserId());
                $adminField->setCreated(new \DateTime());
                $adminField->setModifiedUserId($adminModule->getModifiedUserId());
                $adminField->setModified(new \DateTime());
                $adminField->setStatus($valField->getStatus());

                $service->getAdminEntityManager()->persist($adminField);
                $service->getAdminEntityManager()->flush();

                $selectValues = $service->getDefaultEntityManager()->getRepository('DynamicListModule\Entity\DynamicListModuleSelectValues')->findBy(array('field' => $valField->getId()));

                foreach($selectValues as $valSelect) {
                    $selectValue = new \DynamicListModule\Entity\Admin\DynamicListModuleSelectValues();
                    $selectValue->setName($valSelect->getName());
                    $selectValue->setField($adminField->getId());                

                    $service->getAdminEntityManager()->persist($selectValue);
                    $service->getAdminEntityManager()->flush();
                }
            }
            $this->flashMessenger()->addSuccessMessage('Module has been pushed to the Admin DLM Repository');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));
        }
    }

    public function installmoduleAction()
    {
        $service = $this->getServiceLocator()->get('phoenix-dynamiclistmodule-install');

        $moduleId = (int) $this->params()->fromRoute('itemId', 0);

        if (empty($moduleId)) {
            $this->flashMessenger()->addErrorMessage('Invalid Module Id Given for Push');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));
        }

        $module = $service->getAdminItem($moduleId);

        if (empty($module)) {
            $this->flashMessenger()->addErrorMessage('Invalid Module Id Given for Push');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));            
        }

        $localModule = $service->getItemBy(array('adminRepoId' => $moduleId));

        if (!empty($localModule)) {
            //Eventually we'll use this to update/sync the local module with modifications, but that is in the future.
            //For now, we'll just trigger a notice
            $this->flashMessenger()->addInfoMessage('Module already Exists Locally');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));                        
        } else {
            $newModule = new \Toolbox\Entity\Components();
            $newModule->setName($module->getName());
            $newModule->setLabel($module->getLabel());
            $newModule->setDescription($module->getDescription());
            $newModule->setDynamic(1);
            $newModule->setCategories($module->getCategories());
            $newModule->setCreatedUserId($service->getCurrentUser()->getId());
            $newModule->setModifiedUserId($service->getCurrentUser()->getId());
            $newModule->setCreated(new \DateTime());
            $newModule->setModified(new \DateTime());
            $newModule->setAdminRepoId($moduleId);
            $newModule->setStatus(1);
            $newModule->setCasEnabled($module->getCasEnabled());
            $newModule->setCaseAllowed($module->getCasAllowed());

            $service->getDefaultEntityManager()->persist($newModule);
            $service->getDefaultEntityManager()->flush();

            $fields = $module->getComponentFields();

            foreach ($fields as $valField) {
                $newField = new \Toolbox\Entity\ComponentFields();
                $newField->setComponent($newModule);
                $newField->setName($valField->getName());
                $newField->setLabel($valField->getLabel());
                $newField->setTranslate($valField->getTranslate());
                $newField->setType($valField->getType());
                $newField->setShowInList($valField->getShowInList());
                $newField->setOrderNumber($valField->getOrderNumber());
                $newField->setCreatedUserId($newModule->getCreatedUserId());
                $newField->setCreated(new \DateTime());
                $newField->setModifiedUserId($newModule->getModifiedUserId());
                $newField->setModified(new \DateTime());
                $newField->setStatus($valField->getStatus());

                $service->getDefaultEntityManager()->persist($newField);
                $service->getDefaultEntityManager()->flush();

                $selectValues = $service->getDefaultEntityManager()->getRepository('DynamicListModule\Entity\Admin\DynamicListModuleSelectValues')->findBy(array('field' => $valField->getId()));

                foreach($selectValues as $valSelect) {
                    $selectValue = new \DynamicListModule\Entity\DynamicListModuleSelectValues();
                    $selectValue->setName($valSelect->getName());
                    $selectValue->setField($newField->getId());                

                    $service->getDefaulEntityManager()->persist($selectValue);
                    $service->getDefaulEntityManager()->flush();
                }
            }
            $this->flashMessenger()->addSuccessMessage('Module: ' . $newModule->getName() . '  has been installed for this site');
            return $this->redirect()->toRoute('dynamicListModule-toolbox', array('action' => 'install'));
        }
    }        
}
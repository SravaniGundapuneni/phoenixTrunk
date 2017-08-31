<?php
namespace Pages\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ListModule\Controller\SocketsController as BaseSocketsController;
use Zend\View\Model\JsonModel;

class SocketsController extends BaseSocketsController
{
    public function savepagedataAction()
    {
        $serviceManager = $this->getServiceLocator();
        $pagesService   = $serviceManager->get('phoenix-pages');
        $currentUser    = $serviceManager->get('phoenix-users-current');

        $siteroot            = $this->params()->fromPost('siteroot', '');
        $pageContent         = $this->params()->fromPost('content', '');
        $pageContent2        = $this->params()->fromPost('content2', '');
        $pageHeading         = $this->params()->fromPost('pageHeading', '');
        $currentLanguageCode = $this->params()->fromPost('currentLanguageCode', 'en');
        $pageKeyNum          = (int) $this->params()->fromPost('pageKeyNum', 0);

        if (!$currentUser->getUserEntity()) {
            $data = array('success' => 0, 'error' => 'The page cannot be saved. Invalid User.');
        } else if (empty($siteroot)) {
            $data = array('success' => 0, 'error' => 'The page cannot be saved. Invalid site.');
        } else if (empty($pageKeyNum)) {
            $data = array('success' => 0, 'error' => 'The page cannot be saved. Invalid PageId.');
        } else {
            $page = $pagesService->getItem($pageKeyNum);

            $modifiedDate = new \DateTime();

            if ($currentLanguageCode == 'en') {
                $content = array(
                    'blocks'         => $pageContent,
                    'blocks2'        => $pageContent2,
                    'modified'       => $modifiedDate,
                    'modifiedUserId' => $currentUser->getId(),
                    'pageHeading'    => $pageHeading,
                    'skipRates'      => true,
                    'template'       => $page->getTemplate(),
                );
            } else {
                $content = array(
                    'blocks2_' . $currentLanguageCode     => $pageContent2,
                    'blocks_' . $currentLanguageCode      => $pageContent,
                    'modified'                            => $modifiedDate, 
                    'modifiedUserId'                      => $currentUser->getId(),
                    'pageHeading_' . $currentLanguageCode => $pageHeading,
                    'skipRates'                           => true,
                    'template'                            => $page->getTemplate()
                );
            }

            $pagesService->setCurrentUser($currentUser);
            //This will make sure the component is loaded for the Pages module.
            $pagesService->getTranslateFields();
            $pagesService->save($page, $content);

            $data = array(
                'error'       => 'The page content has been saved.',
                'pageContent' => $pageContent,
                'success'     => 1,
            );
        }

        return new JsonModel($data);        
    }
}
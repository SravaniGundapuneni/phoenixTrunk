<?php
namespace MediaManager\Service;

use MediaManager\Model\FileAttachment as FileAttachmentModel;
use Phoenix\Service\ServiceAbstract;

class AttachedMediaFiles extends \ListModule\Service\Lists
{
    const FILE_ATTACHMENTS = 'MediaManager\Entity\MediaManagerFileAttachments';

    protected $allowCas = true;
    protected $casServiceName = 'AttachedMediaFiles';
    protected $parentModule;
    protected $parentItemId;
    protected $currentProperty;
    protected $mediaManager;
    protected $bindParams;
    protected $currentUser;
    protected $entityName = 'MediaManager\Entity\MediaManagerFileAttachments';

    public function setParentInfo($options)
    {
        // this needs to be debugged, something is fishy with currentUser
        // saving as null in db instead of actual user #

        // also, these may be defined already, expected to be injected in Module.php
        // check Lists, find out what is going on

        $this->parentModule    = $options['parentModule'];
        $this->parentItemId    = $options['parentItemId'];
        $this->currentProperty = $options['currentProperty'];
        $this->currentUser     = $options['currentUser'];
    }

    public function getItem($id)
    {
        return $this->getDefaultEntityManager()
                    ->getRepository('\MediaManager\Entity\MediaManagerFileAttachments')
                    ->findOneBy(array('id' => $id));
    }
    
      public function getParentModule($parentModule)
    {
        return $this->getDefaultEntityManager()
                    ->getRepository('\MediaManager\Entity\MediaManagerFileAttachments')
                    ->findBy(array('parentModule' => $parentModule));
    }

    public function getItemForWidgets($parentItemId)
    {
        return $this->getDefaultEntityManager()
                     ->getRepository('\MediaManager\Entity\MediaManagerFileAttachments')
                     ->findOneBy(array('parentItemId' => $parentItemId));
    }

    public function getItemsForWidgets($parentItemId)
    {
         return $this->getDefaultEntityManager()
                     ->getRepository('\MediaManager\Entity\MediaManagerFileAttachments')
                     ->findBy(array('parentItemId' => $parentItemId));
    }

    public function setBindParameters($bindParameters, $bindVariables)
    {
        $bindParamArray = array('parentModule'    => '',
                                'parentItemId'    => 0,
                                'currentProperty' => null,
                                'currentUser'     => null);

        foreach($bindParameters as $valParam) {
            if (isset($bindVariables[$valParam])) {
                switch($valParam) {
                    case 'itemModel':
                        $bindParamArray['parentItemId'] = $bindVariables['itemModel']->getId();
                    case 'module':
                        $bindParamArray['parentModule'] = $bindVariables['module'];
                    default:
                        $bindParamArray[$valParam] = $bindVariables[$valParam];
                        break;
                }
            }
        }

        $this->bindParams = $bindParamArray;
    }

    public function getBindArray()
    {
        $this->setParentInfo($this->bindParams);
        return array('mediaAttachments' => $this->getFiles());
    }

    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    public function getAttachedFileList($viewModel)
    {
        $attachedFIles = $this->getFiles();
    }

    public function getFiles()
    {
        $qbGetFiles = $this->getDefaultEntityManager()->createQueryBuilder();

        //@TODO Add Multilanguage support
        $qbGetFiles->select('mmfa')
                   ->from(self::FILE_ATTACHMENTS, 'mmfa')
                   ->where('mmfa.parentModule = :parentModule')
                   ->andWhere('mmfa.parentItemId = :parentItemId')
                   ->andWhere('mmfa.status = 1')
                   ->andWhere('mmfa.langCode = :langCode')
                   ->setParameter('parentModule', $this->parentModule)
                   ->setParameter('parentItemId', $this->parentItemId)
                   ->setParameter('langCode', 'all');

        $result = $qbGetFiles->getQuery()->getResult();

        $filesArray = array();

        if (count($result)) {
            foreach ($result as $valAttachment) {
                $filesArray[] = $this->createModel($valAttachment);
            }
        }

        return $filesArray;
    }

    public function fileIsAttached($file)
    {
        $qbIsAttached = $this->getDefaultEntityManager()->createQueryBuilder();

        $fileInfo = pathinfo($file);

        $qbIsAttached->select('mmfa')
                   ->from(self::FILE_ATTACHMENTS, 'mmfa')
                   ->join('mmfa.mediaManagerFile', 'mmf')
                   ->where('mmfa.parentModule = :parentModule')
                   ->andWhere('mmfa.parentItemId = :parentItemId')
                   ->setParameter('parentModule', $this->parentModule)
                   ->setParameter('parentItemId', $this->parentItemId);

        if (is_int($file)) {
            $qbIsAttached->andWhere('mmf.id = :fileId')
                         ->setParameter('fileId', $file);
        } else {
           $qbIsAttached->andWhere('mmf.path = :path')
                        ->andWhere('mmf.name = :name')
                        ->setParameter('path', $fileInfo['dirname'])
                        ->setParameter('name', $fileInfo['basename']);            
        } 

        $result = $qbIsAttached->getQuery()->getResult();

        if (count($result)) {
            $attachment = $result[0];
            return $this->createModel($attachment);
        } else {
            return null;
        }
    }

    public function save($model, $data, $approved = false, $moduleName = null)
    {
        $data['mediaManagerFile'] = $this->getFileItem($data['file'])->getEntity();
        $result =  parent::save($model, $data, $approved);
        
        
        $cacheKey = 'mediaattach-' . $data['parentModule'] . '-' . $data['parentItemId'];
        $this->deleteFromCache($cacheKey);
        $cacheKey = 'mediaattach-' . ucfirst($data['parentModule']) . '-' . $data['parentItemId'];
        $this->deleteFromCache($cacheKey);
        
        return $result;
    }
    
    public function attachFile($file, $orderNumber = -1, $approved = false)
    {
        $fileItem = $this->getFileItem($file);

        if (!$fileItem) {
            throw new \Exception('Invalid FileItem - ' . $file);
            return false;
        }

        $newAttachment = $this->createModel(new \MediaManager\Entity\MediaManagerFileAttachments());
        $newAttachment->getEntity()->setParentModule($this->parentModule);
        $newAttachment->getEntity()->setParentItemId($this->parentItemId);

        if (is_object($this->currentProperty)) {
            $newAttachment->getEntity()->setPropertyId($this->currentProperty->getId());
        }

        $now = new \DateTime();

        //@TODO make this polytext compatible.
        //$newAttachment->getEntity()->setFile($fileItem->getEntity()->getId());
        $newAttachment->getEntity()->setMediaManagerFile($fileItem->getEntity());
        $newAttachment->getEntity()->setLangCode('all');
        $newAttachment->getEntity()->setUserId($this->currentUser->getId());
        $newAttachment->getEntity()->setStatus(FileAttachmentModel::DEFAULT_ITEM_STATUS);

        if ($orderNumber > -1) {
            $newAttachment->getEntity()->setOrderNumber($orderNumber);
        }
        
        $module = $this->getDefaultEntityManager()
                       ->getRepository('Toolbox\Entity\Components')
                       ->findOneBy(array('name' => 'AttachedMediaFiles'));

        if ((!$approved) && $module->getCasAllowed() && $module->getCasEnabled()) {
            $data         = $newAttachment->toArray();
            $data['file'] = $file;
            unset($data['mediaManagerFile']);
            $this->createCasEntry('save', $data );
        } else {
            $newAttachment->save();
        }
        
        $cacheKey = 'mediaattach-' . $this->parentModule . '-' . $this->parentItemId;

        $this->deleteFromCache($cacheKey);        
        return $newAttachment;
    }

    protected function deleteFromCache($cacheKey)
    {
        $resultCacheImpl = $this->getDefaultEntityManager()->getConfiguration()->getResultCacheImpl();

        if ($resultCacheImpl instanceof \Doctrine\Common\Cache\ApcCache) {
            $resultCacheImpl->delete($cacheKey);
        }
    }

    public function removeFile($attachmentItemId, $approved = false)
    {
        //A little bit of error trapping
        $itemId = empty($attachmentItemId) ? 0 : $attachmentItemId;

        $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(
            array('name' => 'AttachedMediaFiles'));

        if ((!$approved) && $module && $module->getCasAllowed() && $module->getCasEnabled()) {
            $this->createCasEntry('trash', null, $itemId);
        } else {
            $qbDelete = $this->getDefaultEntityManager()->createQueryBuilder();

            $item = $this->getItem($attachmentItemId);

            if (empty($item)) {
                return true;
            }

            //@TODO refactor these queries so we don't duplicate so much code
            $qbDelete->delete('MediaManager\Entity\MediaManagerFileAttachments', 'mmfa')
                //->where('mmfa.parentModule = :parentModule')
                //->andWhere('mmfa.parentItemId = :parentItemId')
                ->andWhere('mmfa.id = :itemId')
                //->setParameter('parentModule', $this->parentModule)
                //->setParameter('parentItemId', $this->parentItemId)
                ->setParameter('itemId', $itemId);

            $result = $qbDelete->getQuery()->execute();

            $cacheKey = 'mediaattach-' . $item->getParentModule() . '-' . $item->getParentItemId();

            $this->deleteFromCache($cacheKey);

            //Although CAS is not active, it is referenced here because this is the return value from the Condor socket.
            return ($result) ? \ContentApproval\Service\ContentApproval::ITEM_VERSION_PENDING : false;
        }
    }

    /**
     * @param  [type] $orderedItems this assumes we have ordered data
     * @return [type]               [description]
     */
    public function saveAttachmentOrder($options)
    {
        $qbUpdateOrder = $this->getDefaultEntityManager()->createQueryBuilder();

        $qbUpdateOrder->update(self::FILE_ATTACHMENTS, 'mmfa')
                 ->set('mmfa.orderNumber', ":orderNumber")
                 ->where('mmfa.parentModule = :parentModule')
                 ->andWhere('mmfa.parentItemId = :parentItemId')
                 ->andWhere('mmfa.id = :attachId')
                 ->setParameter('parentModule', $options['parentModule'])
                 ->setParameter('parentItemId', $options['parentItemId']);

        // $orderNumber = 1;
        foreach ($options['orderedItems'] as $attachId => $orderNumber) {
            $qbUpdateOrder->setParameter('orderNumber', $orderNumber);
            $qbUpdateOrder->setParameter('attachId', $attachId);
            $qbUpdateOrder->getQuery()->execute();
        }
    }

    public function saveModuleAttachmentAltText($options)
    {
        $queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
        $queryBuilder->update(self::FILE_ATTACHMENTS, 'mmfa')
                     ->set('mmfa.altText', ':altText')
                     ->where('mmfa.id = :attachId')
                     ->setParameter('altText', $options['altText'])
                     ->setParameter('attachId', $options['attachId'])
                     ->getQuery()->execute();
    }

    public function getFileItem($file)
    {
        if (is_int($file)) {
            $parameters = array('id' => $file);
        } else {
            $fileInfo = pathinfo($file);
            $parameters = array('name' => $fileInfo['basename'], 'path' => $fileInfo['dirname']);
        }

        $fileItem = $this->mediaManager->getItemBy($parameters);

        return $fileItem;
    }

    public function createModel($entity = null)
    {
        $model = new FileAttachmentModel($this->getConfig());
        $model->setDefaultEntityManager($this->getDefaultEntityManager());

        if ($entity) {
            $model->setEntity($entity);
        }

        return $model;
    }    
}
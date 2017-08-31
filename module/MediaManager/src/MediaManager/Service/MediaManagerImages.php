<?php

namespace MediaManager\Service;

use MediaManager\Model\MediaManagerImage;
use ListModule\Model\ListItem;

class MediaManagerImages extends \ListModule\Service\Lists
{
    public function __construct()
    {
        $this->entityName = MediaManagerImage::MEDIAMANAGERIMAGE_ENTITY_NAME;
        $this->modelClass = "\MediaManager\Model\MediaManagerImage";
    }

    /**
     * getImage
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \MediaManager\Model\MediaManagerImage
     */
    public function getImage($fileId)
    {
        $em = $this->getDefaultEntityManager();

        $entity = $em->getRepository('MediaManager\Entity\MediaManagerImage')->findOneByFileId($fileId);

        return $entity;

        // $entityManager = $this->getDefaultEntityManager();
        // $entityRepository = $entityManager->getRepository($this->entityName);
        // $entity = $entityRepository->findOneBy($filters);
        // $result = is_null($entity) ? null : $this->createModel($entity);
    }

    public function setAltText($fileId, $altText)
    {
        $queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
        $queryBuilder->update($this->entityName, 'mmi')
                 ->set('mmi.altText', '?1')
                 ->where('mmi.fileId = ?2')
                 ->setParameter(1, $altText)
                 ->setParameter(2, $fileId)
                 ->getQuery()->execute();
    }

    public function getAltText($fileId)
    {
        $image = $this->getImageFromFileId($fileId);
        return $image->getAltText();
    }

    public function getImageId($fileId)
    {
        $image = $this->getImageFromFileId($fileId);
        return $image->getId();
    }

    public function getImageByName($name)
    {
        $em = $this->getDefaultEntityManager();
        $file = $em->getRepository('MediaManager\Entity\MediaManagerFiles')->findOneByName($name);
        $fileId = $file->getId();
        $image = $em->getRepository('MediaManager\Entity\MediaManagerImage')->findOneByFileId($fileId);
        return $image;
    }

    public function bestMatch($imageSource, $parameters)
    {
        $bestMatch = array();

        /**
         * TODO: media manager needs to tell us where the image its at,
         * maybe we should pull this fromt he ImageSwitch table
         */
        $mediaRoot = '/d/';
        $siteroot  = $this->getConfig()->get(array('templateVars', 'siteroot'));

        $bestMatch['imageSwitchId'] = null;
        if ($parameters['default']) {
            $bestMatch['imageValue'] = $parameters['src'];
        } else {
            $bestMatch['imageValue'] = $siteroot . $mediaRoot . $parameters['src'];
        }
        $bestMatch['altTags']      = $parameters['alt'];
        $bestMatch['imageWidth']   = $parameters['width'];
        $bestMatch['imageHeight']  = $parameters['height'];
        $bestMatch['addThisTitle'] = $parameters['addThisTitle'];
        $bestMatch['addThisUrl']   = $parameters['addThisUrl'];

        foreach ($parameters as $key => $modifier) {
            switch ($modifier) {
                
            }
        }

        /**
         * Lets return any extra parameters
         */
        $bestMatch = array_merge($bestMatch, $parameters);

        $entityManager = $this->defaultEntityManager;

        /**
         * This will be replaced by code that actually uses Doctrine's ORM.
         */
        $image = $entityManager->getConnection()->fetchAssoc("
            SELECT * FROM imageSwitch
            WHERE Status = 1
            ORDER BY created DESC LIMIT 1"
        );

        if (!is_null($image) && $image) {
            $bestMatch['imageSwitchId'] = $image['imageSwitchId'];
        }

        return $bestMatch;
    }

    public function getImageBy(array $filters = array())
    {
        $entityManager = $this->getDefaultEntityManager();
        $entityRepository = $entityManager->getRepository($this->entityName);
        $entity = $entityRepository->findOneBy($filters);
        $result = is_null($entity) ? null : $this->createModel($entity);
        return $result;
    }

    public function createMediaManagerImageModel($imageEntity = false)
    {
        $imageModel = new MediaManagerImage($this->getConfig());
        $imageModel->setDefaultEntityManager($this->getDefaultEntityManager());
        $imageModel->setAdminEntityManager($this->getAdminEntityManager());

        if ($imageEntity) {
            $imageModel->setMediaManagerImageEntity($imageEntity);
        }

        return $imageModel;
    }

    public function dynaImages()
    {
        $img = $this->getImageBy();
        return array(
            'image' => $img->getImage(),
            'imageHeight' => $img->getImageHeight(),
            'imageWidth' => $img->getImageWidth()
        );
    }
    
    public function currentDefaultImage()
    {
        $em = $this->getDefaultEntityManager();
        $result = $em->getRepository('MediaManager\Entity\MediaManagerFiles')->findOneByPath('/d/default');
        return $result;
    }
    
    public function deleteDefaultImage()
    {
        $qBldr = $this->getDefaultEntityManager()->createQueryBuilder();
        $result = $qBldr->delete('MediaManager\Entity\MediaManagerFiles', 'mmi')->
                            where("mmi.path=:path")->
                            setParameter('path', '/d/default')->
                            getQuery()->execute();
        return $result;
    }   

    private function getImageFromFileId($fileId)
    {
        $em = $this->getDefaultEntityManager();
        $entity = $em->getRepository('MediaManager\Entity\MediaManagerImage')->findOneByFileId($fileId);
        if (!$entity) {
            throw new \Exception('Image with fileId ' . $fileId . ' does not exist');
        }      
        return $entity;
    }
}
<?php

/**
 * The Lists Service File
 *
 * This file contains the Lists Class, which is the base class for all modules that are 
 * built off of this module. 
 *
 * This should be the base for all of our listModules. It SHOULD NOT become a catch all for 
 * our modules and become unwieldy. This exists as a way to minimize duplicated code, of which the CRUD
 * of list modules tend to have a lot.
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace ListModule\Service;

use Phoenix\StdLib\ArrayHelper;
use \ListModule\Model\ListItem;
use Zend\Mail\Message;

use Phoenix\EventManager\Event as LanguageEvent;

use Zend\Stdlib\ArrayObject;

/**
 * The Lists Service Class
 *
 * This should be the base for all of our listModules. It SHOULD NOT become a catch all for 
 * our modules and become unwieldy. This exists as a way to minimize duplicated code, of which the CRUD
 * of list modules tend to have a lot.
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 */
abstract class UnifiedLists extends Lists
{
    protected $dataItem;

    protected $languagesEntity = 'Languages\Entity\UnifiedLanguageTranslations';

    /**
     * getItems
     * 
     * @return array
     */
    public function getItems($active = false, $showAll = false)
    {

        $this->getTranslateFields();

        //Create the QueryBuilder instance
        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();

        $this->buildItemsQuery($qbItems);

        // echo ($qbItems->getQuery()->getSql()) . '<br>';
        // var_dump($qbItems->getQuery()->getParameters());
        // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
        //Run the Query

        $result = $qbItems->getQuery()->getResult();

        $arrayItems = array();

        if (empty($result)) {
            return array();
        }

        return $this->getItemsTranslated($result);
    }

    protected function buildItemsQuery($queryBuilder)
    {
        $currentProperty = $this->getServiceManager()->get('currentProperty');

        $module = $this->getModule();

        if (!is_object($module)) {
            $this->getTranslateFields();

            $module = $this->getModule();
        }

        //Build the Query
        $queryBuilder->select('ci')
                ->from($this->entityName, 'ci')
                ->leftJoin('ci.component', 'c')
                ->leftJoin('ci.languageTranslations', 'lt')
                ->where('c.name = :moduleName')
                ->andWhere(
                        $queryBuilder->expr()->orx(
                            'ci.id = lt.item',
                            'lt.item is NULL'
                        )
                    )
                ->setParameter('moduleName', $module->getName());

        if ($active == true) {
            $queryBuilder->andWhere('ci.status = 1');
        }

        if (!$this->getCurrentUser()->isCorporate()) {
            $queryBuilder->andWhere($queryBuilder->expr()->orx(
                            'ci.allProperties = 1', 
                            'ci.property = :property'
                        )
                    )
                    ->setParameter('property', $currentProperty->getEntity());
        }
    }

    public function getItemsTranslated($items)
    {
        $translatedItems = array();
        foreach ($items as $valItem) {
            $translatedItems[] = $this->createModel($valItem);
        }

        return $translatedItems;        
    }

    /**
     * getItem
     * @param  int $id
     * @return mixed
     */
    public function getItem($id)
    {
        $this->getModuleName();
        //This will load the module if necessary
        $this->getTranslateFields();

        $module = $this->getModule();

        // var_dump($this->moduleFields);

        $entity = $this->getDefaultEntityManager()->getRepository($this->entityName)->findOneBy(array('id'=>$id));

        if (empty($entity)) {
            return false;
        }

        return $this->createModel($entity);
    }

    /**
     * getItemBy
     * @param  array  $filters
     * @return mixed
     */
    public function getItemBy($filters = array())
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        $query = $this->getFilteredQuery($filters);

        $result = $query->getQuery()->getResult();

        if (empty($result)) {
            return false;
        }

        $entity = current($result);

        return $this->createModel($entity);
    }

    public function getFilteredQuery($filters)
    {
        $qbFiltered = $this->getDefaultEntityManager()->createQueryBuilder();
        $qbFiltered->select('ci')
                ->from($this->entityName, 'ci')
                ->leftJoin("ci.{$this->dataItem}", 'ed')
                ->where('ci.component = :component')
                ->setParameter('component', $this->getModule());

        foreach ($filters as $keyFilter => $valFilter) {
            switch ($keyFilter) {
                case 'property':
                    $qbFiltered->andWhere($qbFiltered->expr()->orx(
                                    'ci.allProperties = 1', 
                                    'ci.property = :property'
                                )
                            )
                            ->setParameter('property', $valFilter);
                    break;
                case 'status':
                    $qbFiltered->andWhere('ci.status = :status')
                               ->setParameter('status', $valFilter);
                    break;
                default:
                    $qbFiltered->andWhere("ed.$keyFilter = :$keyFilter")
                               ->setParameter($keyFilter, $valFilter);
                    break;
            }
        }

        return $qbFiltered;
    }    

    /**
     * getItemsBy
     * @param  array  $filters
     * @return mixed
     */
    public function getItemsBy($filters = array())
    {
        //This will load the module if necessary
        $this->getTranslateFields();

        $query = $this->getFilteredQuery($filters);

        $result = $query->getQuery()->getResult();

        if (empty($result)) {
            return array();
        }

        foreach ($result as $valItem) {
            $items[] = $this->createModel($valItem);
        }

        return $items;
    }

    /**
     * createModel
     * @param  boolean $entity
     * @return object $model
     */
    public function createModel($entity=false, $languageTranslations = array())
    {
        $modelClass = $this->modelClass;
        $moduleFields = is_object($this->getModule()) ? $this->getModule()->getComponentFields() : array();

        $model = new $modelClass($this->getConfig(), $moduleFields);
        $model->setDefaultEntityManager($this->getDefaultEntityManager());
        $model->setAdminEntityManager($this->getAdminEntityManager());
        $model->setServiceManager($this->serviceManager);

        $model->setLanguageTranslations($languageTranslations);

        if (is_object($this->serviceManager) && $this->serviceManager->has('MergedConfig')) {
            $model->setLanguages($this->serviceManager->get('phoenix-languages'));
        }
        $model->setCurrentUser($this->currentUser);
        $model->setCurrentLanguage($this->getCurrentLanguage());

        if ($entity) {
            $model->setEntity($entity);
            $model->setLanguageTranslations($entity->getLanguageTranslations());
        }

        return $model;
    }

    /**
     * draft
     * @param  array $items
     * @return void
     */
    public function draft($items, $approved = false)
    {
        if ((!$approved) && $this->allowCas) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus()!= ListItem::ITEM_STATUS_DRAFT)
                {
               $this->createCasEntry('draft', array('status'=>ListItem::ITEM_STATUS_DRAFT), $item, array('status'=>$this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_DRAFT, $items);
        }
    }

    /**
     * archive
     * @param  array $items
     * @return void
     */
    public function archive($items, $approved = false)
    {
        if ((!$approved) && $this->allowCas) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus()!= ListItem::ITEM_STATUS_ARCHIVED)
                {
                $this->createCasEntry('archive', array('status'=>ListItem::ITEM_STATUS_ARCHIVED), $item, array('status'=>$this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_ARCHIVED, $items);
        }
        
        
    }

    /**
     * publish
     * @param  array $items
     * @return void
     */
    public function publish($items, $approved = false)
    {
        if ((!$approved) && $this->allowCas) {
            foreach ($items as $item) {
                if ($this->getItem($item) && ($this->getItem($item)->getStatus()!= ListItem::ITEM_STATUS_PUBLISHED))
                {
                    $this->createCasEntry('publish', array('status'=>ListItem::ITEM_STATUS_PUBLISHED), $item, array('status'=>$this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_PUBLISHED, $items);
        }
    }

    /**
     * trash
     * @param  array $items
     * @return void
     */
    public function trash($items, $approved = false)
    {
        if ((!$approved) && $this->allowCas) {
            foreach ($items as $item) {
                if ($this->getItem($item)->getStatus()!= ListItem::ITEM_STATUS_TRASHED)
                {
                $this->createCasEntry('trash', array('status'=>ListItem::ITEM_STATUS_TRASHED), $item, array('status'=>$this->getItem($item)->getStatus()));
                }
            }
        } else {
            $this->updateItemsStatuses($this->entityName, 'p', ListItem::ITEM_STATUS_TRASHED, $items);
        }
    }

    // Method is for getting results on various queries that might be implemented in extended classes
    // Outside calls have to be done in a form of "getSomething()"
    // internal implementation of 'filler' methods should be "Something($query, $arg)" in order not to conflict with this method
    public function __call($name, $args)
    {
        // Check if a 'getter' was called
        if (strpos($name, 'get') !== 0
                // ...and the required method exists on current object
                || !method_exists($this, $fun = substr($name, 3))) {
            return null;
        }
        // Create the query object
        $qry = $this->getDefaultEntityManager()->createQueryBuilder();
        // Call the query builder method in the extended object to actually fill the query object
        $this->$fun($qry, $args[0]);
        // return the query results
        return $qry->getQuery()->getResult();
    }

    public function hasChanges($entityModel, $data)
    {
        $entity = $entityModel->getEntity();

        $hasChanges = false;

        if (empty($entity)) {
            $hasChanges = true;
        } else {
            foreach ($data as $key => $value) {
                $getAttribute = 'get' . ucfirst($key);
                if (($entity->$getAttribute()) != $value) {
                    $hasChanges = true;
                    break;
                }
            }
        }

        return $hasChanges;
    }

    public function exportTranslations(LanguageEvent $e)
    {
        $translations = $e->getParam('exportArray');

        $this->getTranslateFields();

        $module = $this->getModule();

        $moduleItems = $this->getItems();

        $moduleName = $module->getName();

        foreach ($moduleItems as $valItem) {
            if (!isset($translations[$moduleName])) {
                $translations[$moduleName] = new ArrayObject();
            }

            $itemArray = new ArrayObject();
            $itemArray['fields'] = new ArrayObject();

            $itemTranslations = $valItem->getTranslations();

            foreach ($module->getComponentFields() as $valField) {
                if ($valField->getTranslate() == 1) {
                    $fieldArray = new ArrayObject();

                    $fieldName = 'get' . ucfirst($valField->getName());
                    $fieldArray['defaultLanguageValue'] = $valItem->$fieldName();
                    $fieldArray['translations'] = new ArrayObject();

                    if (isset($itemTranslations[$valField->getName()])) {
                        $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                    }

                    $itemArray['fields'][$valField->getName()] = $fieldArray;
                }
            }

            $translations[$moduleName][$valItem->getId()] = $itemArray;            
        }

        //This is a necessary step so wierd stuff doesn't go down.
        foreach ($this->detachEntities as $valEntity) {
            $this->getDefaultEntityManager()->detach($valEntity);
        }
    }

    public function importTranslations(LanguageEvent $e)
    {
        $importText = $e->getParam('importText');

        $languagesService = $this->getServiceManager()->get('phoenix-languages');

        $languages = $languagesService->getItems();

        $languagesArray = array();

        $this->getTranslateFields();

        $module = $this->getModule();

        foreach ($languages as $valLanguage) {
            $languagesArray[$valLanguage->getCode()] = $valLanguage;
        }

        $this->doImportTranslations($languagesArray, $importText, $module);

    }

    public function doImportTranslations($languagesArray, $importText, $module = null)
    {
        if (isset($importText[$module->getName()])) {
            foreach ($importText[$module->getName()] as $keyItem => $valItem) {
                $item = $this->getItemBy(array('id' => $keyItem, 'component' => $module->getEntity()));

                $itemTranslations = $item->getLanguageTranslations();

                $translationsArray = array();

                foreach ($itemTranslations as $valItemTranslation) {
                    $translationsArray[$valItemTranslation->getField()->getName()][$valItemTranslation->getLanguage()->getCode()] = $valItemTranslation;
                }

                foreach ($module->getComponentFields() as $valField) {
                    if ($valField->getTranslate() == 1) {
                        $importTranslations = $valItem['fields'][$valField->getName()]['translations'];

                        foreach ($importTranslations as $keyTranslation => $valTranslation) {
                            if (isset($translationsArray[$valField->getName()][$keyTranslation]) && $translationsArray[$valField->getName()][$keyTranslation]->getField() == $valField) {
                                $updateTranslation = $translationsArray[$valField->getName()][$keyTranslation];
                                $updateTranslation->setContent($valTranslation);
                                $updateTranslation->setModified(new \DateTime());
                                $updateTranslation->setModifiedUserId($this->getCurrentUser()->getId());
                                $this->getDefaultEntityManager()->persist($updateTranslation);
                                $this->getDefaultEntityManager()->flush();
                                echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Updated.<br>';
                            } else {
                                $this->insertTranslation($valField, $module, $languagesArray[$keyTranslation], $item, $valTranslation);
                                echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Inserted.<br>';
                            }
                        }
                    }
                } 
            } 
        }
    }

    public function insertTranslation($field, $module, $language, $item, $content)
    {
        $insertTranslation = new \Languages\Entity\LanguageTranslations();
        $insertTranslation->setField($field);
        $insertTranslation->setComponent($valModule->getEntity());
        $insertTranslation->setLanguage($language);
        $insertTranslation->setContent($content);
        $insertTranslation->setCreatedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setModifiedUserId($this->getCurrentUser()->getId());
        $insertTranslation->setCreated(new \DateTime());
        $insertTranslation->setModified(new \DateTime());
        $insertTranslation->setStatus(1);
        $this->getDefaultEntityManager()->persist($insertTranslation);
        $this->getDefaultEntityManager()->flush();

        $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ? where translationId = ?', array($item, $insertTranslation->getId()));
    }    
}

<?php

/**
 * The PhoenixSite Service
 *
 * @category    Toolbox
 * @package     PhoenixSite
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.6
 * @since       File available since release 13.6
 * @author      Sravani Gundapuneni <sgundapuneni@travelclick.com>
 * @filesource
 */

namespace PhoenixSite\Service;

use PhoenixSite\Model\SiteComponent;
use Phoenix\EventManager\Event as LanguageEvent;
use Zend\Stdlib\ArrayObject;
use ListModule\Service\UnifiedLists;

class Components extends UnifiedLists {

    protected $modelClass = "\PhoenixSite\Model\SiteComponent";

    public function __construct() {
        $this->entityName = SiteComponent::ENTITY_NAME;
        $this->dataEntity = SiteComponent::SITE_DATA_ENTITY;
    }

    public function getModuleName() {
        return current(explode('\\', get_class($this)));
    }

    protected function organizeTransItems($items = array())
    {
        $itemsArray = array();
        $components = array();

        foreach ($items as $valItem) {
            $itemsArray[$valItem->getId()]['item'] = $valItem;
            $itemsArray[$valItem->getId()]['languageTranslations'] = array();

            if (!isset($components[$valItem->getComponent()->getId()])) {
                $components[$valItem->getComponent()->getId()] = $valItem->getComponent();
            }
        }

        $this->transComponents = array_keys($components);

        return $itemsArray;
    }

    protected function buildTransQuery($qbTrans)
    {
        $qbTrans->where($qbTrans->expr()->in('lt.component', ':components'))
            ->setParameter('components', $this->transComponents);
    }

    // public function getItemTranslations($items = array()) {
    //     $itemsArray = $t

    //     $itemIds = array_keys($itemsArray);

    //     $languageTranslations = $this->getDefaultEntityManager()->getRepository('Languages\Entity\LanguageTranslations')->findBy(array('component' => $components, 'item' => $itemIds));

    //     if (!empty($languageTranslations)) {
    //         foreach ($languageTranslations as $valTranslation) {

    //             if (is_object($valTranslation->getItem())) {
    //                 $valTranslation->getItem()->__setInitialized(true);
    //                 $itemId = $valTranslation->getItem()->getId();
    //                 $this->detachEntities[] = $valTranslation->getItem();                    
    //             } else {
    //                 $itemId = $valTranslation->getItem();
    //             }

    //             if (!isset($itemsArray[$itemId])) {
    //                 continue;
    //             }

    //             $itemsArray[$itemId]['languageTranslations'][] = $valTranslation;
    //         }
    //     }

    //     return $itemsArray;
    // }

    public function getTranslateFields() {
        if (empty($this->translateFields)) {
            $module = $this->getModule();

            if (empty($module)) {
                $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(array('name' => 'Footer'));
                $this->setModule($module);
            } elseif (is_int($module)) {
                $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(array('id' => $module));
            }

            if (!empty($module)) {
                $fields = $module->getComponentFields();

                $translateFields = array();

                foreach ($fields as $valField) {
                    if ($valField->getTranslate() == 1) {
                        $translateFields[] = array($valField->getName(), $valField->getType());
                    }
                }

                $this->translateFields = $translateFields;
            }
        }

        return $this->translateFields;
    }

    public function exportTranslations(LanguageEvent $e) {
        $translations = $e->getParam('exportArray');

        $this->getTranslateFields();

        $module = $this->getModule();


        $moduleItems = $this->getItems();

        //$moduleName = $module->getName();

        foreach ($moduleItems as $valItem) {
            $moduleName = $valItem->getComponent()->getName();

            if (!isset($translations[$moduleName])) {
                $translations[$moduleName] = new ArrayObject();
            }

            $itemArray = new ArrayObject();
            $itemArray['fields'] = new ArrayObject();

            $itemTranslations = $valItem->getTranslations();
      
            //PhoenixSiteComponent items currently have a 1:1 relationship to fields
            $valField = $valItem->getComponentFields();

            if ($valField->getTranslate() == 1) {
                $fieldArray = new ArrayObject();

                $fieldArray['defaultLanguageValue'] = $valItem->getLabel();
                $fieldArray['translations'] = new ArrayObject();

                if (isset($itemTranslations['label'])) {
                    $fieldArray['translations'] = $itemTranslations['label'];
                }

                $itemArray['fields'][$valField->getName()] = $fieldArray;
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
        $this->getDefaultEntityManager()->clear();
        $importText = $e->getParam('importText');

        $languagesService = $this->getServiceManager()->get('phoenix-languages');

        $languages = $languagesService->getItems();

        $languagesArray = array();

        foreach ($languages as $valLanguage) {
            $languagesArray[$valLanguage->getCode()] = $valLanguage;
        }

        $modules = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findBy(array('dynamic' => 2));

        foreach ($modules as $valModule) {
            $this->doImportTranslations($languagesArray, $importText, $valModule);
        } 
    }

    public function doImportTranslations($languagesArray, $importText, $module = null) {
        if (isset($importText[$module->getName()])) {
            $name = $module->getName();
            foreach ($importText[$module->getName()] as $keyItem => $valItem) {
                $componentItem = $this->getItem($keyItem);

                echo 'Begin Static SiteComponent ' . $name . ' Import of item #' . $keyItem . '<hr><hr>';

                if (empty($componentItem)) {
                    continue;
                }

                $translations = $valItem['fields'][$componentItem->getComponentFields()->getName()]['translations'];

                if (empty($translations)) {
                    continue;
                }

                $qb = $this->getDefaultEntityManager()->createQueryBuilder();
                $qb->update('Languages\Entity\LanguageTranslations', 'u')
                    ->set('u.content', '?1')
                    ->set('u.modified', "'" . date('Y-m-d H:i:s') . "'")
                    ->set('u.modifiedUserId', $this->getCurrentUser()->getId())
                    ->where('u.item = ?3')
                    ->andWhere('u.component = ?4')
                    ->andWhere('u.field = ?5')
                    ->andWhere('u.language = ?6')
                    ->setParameter(3, $keyItem)
                    ->setParameter(4, $module)
                    ->setParameter(5, $componentItem->getComponentFields());


                foreach ($translations as $keyTranslation => $valTranslation) {
                    $qb->setParameter(1, $valTranslation);
                    $qb->setParameter(6, $languagesArray[$keyTranslation]->getEntity());
                }

                $q = $qb->getQuery();
                //Don't want this to error out.
                if (count($q->getParameters()) != 5) {
                    continue;
                }
                $p = $q->execute();
            }
        }
    }

//    public function importTranslations(LanguageEvent $e) {
//        $importText = $e->getParam('importText');
//
//        $languagesService = $this->getServiceManager()->get('phoenix-languages');
//
//        $languages = $languagesService->getItems();
//
//        $languagesArray = array();
//
//        $this->getTranslateFields();
//
//        $module = $this->getModule();
//
//        foreach ($languages as $valLanguage) {
//            $languagesArray[$valLanguage->getCode()] = $valLanguage;
//        }
//
//        $this->doImportTranslations($languagesArray, $importText, $module);
//    }
}

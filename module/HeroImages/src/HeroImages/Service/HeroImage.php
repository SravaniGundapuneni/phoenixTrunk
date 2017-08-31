<?php
/**
 * The HeroImages Service
 *
 * @category    Toolbox
 * @package     HeroImages
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Daniel Yang <dyang@travelclick.com>
 * @filesource
 */
namespace HeroImages\Service;

use HeroImages\Model\HeroImages as HeroImageModel;
use HeroImages\Entity\HeroImages as HeroImageEntity;
use Phoenix\EventManager\Event as LanguageEvent;
use Zend\Stdlib\ArrayObject;
use ListModule\Service\UnifiedLists;

class HeroImage extends UnifiedLists
{
    protected $allowCas = true;
    protected $isScan = false;
    protected $entityName;
    protected $categories;
    protected $modelClass = "HeroImages\Model\HeroImages";

    public function __construct()
    {
        $this->entityName = "HeroImages\Entity\HeroImageItems";
        $this->dataEntity = HeroImageModel::HEROIMAGES_DATA_ENTITY;
    }
    
    
    
   public function getPropertyIdOptions ()
    {
        //echo "I am in Pages Service's getHotelOption<br/>";
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }

        return $options;
    }
    
    public function getPageIdOptions ()
    {
        $pagesService = $this->getServiceManager()->get('phoenix-pages');

        $pages = $pagesService->getItems();

        foreach ($pages as $keyPages => $valPages) {
            $options[$valPages->getId()] = $valPages->getPageKey();
        }
        return $options;
    }
    
    
    public function getAttachedPages()
    {
        $qbPage = $this->getDefaultEntityManager()->createQueryBuilder();

        $pagesService = $this->getServiceManager()->get('phoenix-pages');

        $options = array();

        $qbPage->select(p)
               ->from('Pages\Entity\PagesItems','p')
               ->leftjoin('HeroImages\Entity\HeroImages','h',\Doctrine\ORM\Query\Expr\Join::WITH,'p.id = h.pageId' )
               ->where('h.pageId is null' )
               ->andWhere('p.component = :component')
               ->setParameter('component', $pagesService->getModule());
        
        $pages = $qbPage->getQuery()->getResult();     

        // var_dump($this->getModule()->getId());

        // echo $qbPage->getQuery()->getSql();
        // die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);

        foreach ($pages as $keyPages => $valPages) {
            $options[$valPages->getId()] = $valPages->getPagesData()->getPageKey();
        }

        return $options;
    }
    
    public function getCurrentPage($id)
    {
        $pagesService = $this->getServiceManager()->get('phoenix-pages');

        $page = $pagesService->getItem($id);

        $options = array();

        if (!empty($pages)) {
            $options[$id] = $page->getPageKey();
        }

        return $options;
    }

    public function exportTranslations(LanguageEvent $e)
    {
        $translations = $e->getParam('exportArray');

        $this->getTranslateFields();

        $module = $this->getModule();

        $attachmentsService = $this->getServiceManager()->get('phoenix-heroimageAttachments');

        $moduleItems = $this->getItemsBy(array('propertyId' => array(10, 22)));

        $moduleName = $module->getName();

        foreach ($moduleItems as $valItem) {
            if (!isset($translations[$moduleName])) {
                $translations[$moduleName] = new ArrayObject();
            }

            $itemArray = new ArrayObject();
            $itemArray['fields'] = new ArrayObject();

            $itemTranslations = $valItem->getTranslations();

            $attachmentFields = array('text1', 'text2', 'text3');

            foreach ($module->getComponentFields() as $valField) {
                if ($valField->getTranslate() == 1) {
                    $fieldName = 'get' . ucfirst($valField->getName());

                    if (in_array($valField->getName(), $attachmentFields)) {
                        $attachments = $valItem->getMediaAttachments();
                        foreach ($attachments as $valAttachment) {
                            $heroAttachment = $attachmentsService->getItemBy(array('attachmentId' => $valAttachment->getId()));

                            if (!$heroAttachment) {
                                continue;
                            }
                            $fieldArray = new ArrayObject();   

                            $fieldName = 'get' . ucfirst($valField->getName());

                            $defaultLanguageValue = $heroAttachment->$fieldName();

                            $itemTranslations = $heroAttachment->getTranslations();

                            $fieldArray['defaultLanguageValue'] = $defaultLanguageValue;

                            $fieldArray['translations'] = new ArrayObject();

                            $fieldName = $valField->getName() . '_' . $heroAttachment->getId();

                            if (isset($itemTranslations[$valField->getName()])) {
                                $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                            }

                            $itemArray['fields'][$fieldName] = $fieldArray;
                        }
                    } else {
                        $fieldArray = new ArrayObject();                        

                        $defaultLanguageValue = $valItem->$fieldName();
                        $fieldArray['defaultLanguageValue'] = $defaultLanguageValue;
                        $fieldArray['translations'] = new ArrayObject();

                        if (isset($itemTranslations[$valField->getName()])) {
                            $fieldArray['translations'] = $itemTranslations[$valField->getName()];
                        }

                        $itemArray['fields'][$valField->getName()] = $fieldArray;
                    }
                }
            }

            $translations[$moduleName][$valItem->getId()] = $itemArray;
        }
    }

    public function doImportTranslations($languagesArray, $importText, $module = null)
    {
        $this->dontDetach = true;
        if (!$this->getCurrentUser() instanceof \Users\Model\Users) {
            $this->setCurrentUser($this->getServiceManager()->get('phoenix-users-current'));
        }

        $attachmentsService = $this->getServiceManager()->get('phoenix-heroimageAttachments');

        if (isset($importText[$module->getName()])) {
            foreach ($importText[$module->getName()] as $keyItem => $valItem) {
                $moduleEntity = ($module instanceof \Phoenix\Module\Entity\EntityAbstract) ? $module : $module->getEntity();

                $filters = array('id' => $keyItem, 'component' => $moduleEntity);

                $item = $this->getItemBy($filters);

                if (empty($item)) {
                    continue;
                }

                $itemTranslations = $item->getLanguageTranslations();

                $translationsArray = array();

                foreach ($itemTranslations as $valItemTranslation) {
                    $translationsArray[$valItemTranslation->getField()->getName()][$valItemTranslation->getLanguage()->getCode()] = $valItemTranslation;
                }
                echo 'Begin Module ' . $module->getName() . ' Import of item #' . $keyItem . '<hr><hr>';
                $attachmentFields = array('text1', 'text2', 'text3');                        

                $attachments = $item->getMediaAttachments();

                foreach ($module->getComponentFields() as $valField) {
                    if ($valField->getTranslate() == 1) {
                        $useAttachmentId = false;

                        if (in_array($valField->getName(), $attachmentFields)) {
                            $useAttachmentId = true;
                            $attachmentsService->getTranslateFields();
                            $moduleEntity = $attachmentsService->getModule();

                            foreach ($moduleEntity->getComponentFields() as $attachField) {
                                if ($valField->getName() == $attachField->getName()) {
                                    $translateField = $attachField;
                                }
                            }

                            foreach ($attachments as $keyAttachment => $valAttachment) {
                                $heroAttachment = $attachmentsService->getItemBy(array('attachmentId' => $valAttachment->getId()));
                                if (empty($heroAttachment)) {
                                    continue;
                                }
                                $attchmentTranslations = $heroAttachment->getLanguageTranslations();

                                $attachTranslationsArray = array();

                                foreach ($attachmentTranslations as $valItemTranslation) {
                                    $attachTranslationsArray[$valItemTranslation->getField()->getName()][$valItemTranslation->getLanguage()->getCode()] = $valItemTranslation;
                                }

                                foreach ($valItem['fields'] as $keyField => $valTranslateField) {
                                    $translateFieldName = $valField->getName() . '_' . $heroAttachment->getId();
                                    if ($keyField !== $translateFieldName) {
                                        continue;
                                    }

                                    $importTranslations = $valTranslateField['translations'];

                                    if (is_array($importTranslations) || $importTranslations instanceof ArrayObject) {
                                        foreach ($importTranslations as $keyTranslation => $valTranslation) {

                                            if (isset($attachTranslationsArray[$valField->getName()][$keyTranslation]) && $translationsArray[$valField->getName()][$keyTranslation]->getField() == $translateField) {
                                                $updateTranslation = $translationsArray[$valField->getName()][$keyTranslation];
                                                $updateTranslation->setContent($valTranslation);
                                                $updateTranslation->setModified(new \DateTime());
                                                $updateTranslation->setModifiedUserId($this->getCurrentUser()->getId());

                                                $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ?, content = ?, modified = ?, modifiedUserId = ? where translationId = ?', array($heroAttachment->getId(), $valTranslation, $updateTranslation->getModified()->format('Y-m-d H:i:s'), $updateTranslation->getModifiedUserId(), $updateTranslation->getId()));
                                                
                                                //$this->getDefaultEntityManager()->persist($updateTranslation);
                                                // try {
                                                //$this->getDefaultEntityManager()->flush();
                                                // } catch (ORMInvalidArgumentException $e) {
                                                //     //This is why we're going to a unified items table, so this crap can be excised.
                                                    
                                                // }
                                                
                                                //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Updated.<br>';
                                            } else {
                                                $this->insertTranslation($translateField, $moduleEntity, $languagesArray[$keyTranslation], $heroAttachment, $valTranslation);
                                                //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Inserted.<br>';
                                            }
                                        }
                                    }
                                }

                            }

                        } else {
                            $importTranslations = $valItem['fields'][$valField->getName()]['translations'];

                            if (is_array($importTranslations) || $importTranslations instanceof ArrayObject) {
                                foreach ($importTranslations as $keyTranslation => $valTranslation) {

                                    if (isset($translationsArray[$valField->getName()][$keyTranslation]) && $translationsArray[$valField->getName()][$keyTranslation]->getField() == $valField) {
                                        $updateTranslation = $translationsArray[$valField->getName()][$keyTranslation];
                                        $updateTranslation->setContent($valTranslation);
                                        $updateTranslation->setModified(new \DateTime());
                                        $updateTranslation->setModifiedUserId($this->getCurrentUser()->getId());

                                        $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ?, content = ?, modified = ?, modifiedUserId = ? where translationId = ?', array($item->getId(), $valTranslation, $updateTranslation->getModified()->format('Y-m-d H:i:s'), $updateTranslation->getModifiedUserId(), $updateTranslation->getId()));
                                        
                                        //$this->getDefaultEntityManager()->persist($updateTranslation);
                                        // try {
                                        //$this->getDefaultEntityManager()->flush();
                                        // } catch (ORMInvalidArgumentException $e) {
                                        //     //This is why we're going to a unified items table, so this crap can be excised.
                                            
                                        // }
                                        
                                        //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Updated.<br>';
                                    } else {
                                        $this->insertTranslation($valField, $moduleEntity, $languagesArray[$keyTranslation], $item, $valTranslation);
                                        //echo 'Module ' . $module->getName() . '::Item #' . $item->getId() . ' Translation for ' . $valField->getName() . '::' . $keyTranslation . ' Inserted.<br>';
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        $this->dontDetach = false;
    }
}
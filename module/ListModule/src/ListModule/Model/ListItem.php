<?php
/**
 * The ListItem Model File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\Stdlib\ArrayObject;

/**
 * The ListItem Model File
 *
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Model
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       Class available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 */
class ListItem extends \Phoenix\Module\Model\ModelAbstract
{
    const ITEM_STATUS_NEW = 2;
    const ITEM_STATUS_DRAFT = 3;
    const ITEM_STATUS_PUBLISHED = 1;
    const ITEM_STATUS_ARCHIVED = 5;
    const ITEM_STATUS_TRASHED = 9;
    const ITEM_STATUS_PENDING_DELETION = 12;
    const DEFAULT_ITEM_STATUS = 1;

    const YES_LABEL = 'Yes';
    const NO_LABEL = 'No';

    /**
     * The DB Entity
     * @var Module\Entity
     */
    protected $entity;

    /**
     * $inputFilter
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * $inputFactory The factory object used to create input filter items
     * 
     * @var Zend\InputFilter\Factory
     */
    protected $inputFactory;

    /**
     * $currentUser
     * @var Users\Model\User
     */
    protected $currentUser;

    /**
     * $entityClass
     * @var string
     */
    protected $entityClass;

    protected $languageTranslations;

    protected $translations = array();

    protected $fields = array();

    protected $component;

    protected $skipListItemSave = false;

    /**
     * __construct
     */
    public function __construct($config = array(), $fields = array())
    {
        parent::__construct($config);

        $this->inputFilter = new InputFilter;

        $this->setFields($fields);
    }

    public function getLanguages()
    {
        return $this->languages;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    public function setLanguageTranslations($languageTranslations)
    {
        $this->languageTranslations = $languageTranslations;
        $this->organizeTranslations();
    }

    public function getLanguageTranslations()
    {
        return !empty($this->languageTranslations) ? $this->languageTranslations : array();
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    public function getTranslations()
    {
        return $this->translations;
    }    

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function organizeTranslations()
    {
        $translations = array();

        $fields = $this->getFields();

        if (!empty($fields)) {
            foreach ($fields as $valField) {
                $fieldTranslations = array();
                if ($valField->getTranslate() == 1) {
                    $fieldName = $valField->getName();
                    foreach ($this->getLanguageTranslations() as $languageTranslation) {
                        if (is_array($languageTranslation)) {
                            $translateFieldName = $languageTranslation['fieldName'];
                            $translateLangCode = $languageTranslation['langCode'];
                            $translateContent = $languageTranslation['content'];
                        } elseif (is_object($languageTranslation)) {
                            $translateFieldName = $languageTranslation->getField()->getName();
                            $translateLangCode = $languageTranslation->getLanguage()->getCode();
                            $translateContent = $languageTranslation->getContent();
                        }

                        if ($fieldName == $translateFieldName) {
                            $fieldTranslations[$translateLangCode] = $translateContent;
                            continue;
                        }
                    }
                }

                $translations[$valField->getName()] = new ArrayObject($fieldTranslations);
            }
        }

        $translationsObject = new ArrayObject($translations);

        $this->setTranslations($translationsObject);
    }

    protected function checkField($translationField, $modelField) 
    {
        return ($translationField == $modelField);
    }


    public function getTranslation($field, $language)
    {
        if (is_object($field)) {
            $field = $field->getName();
        }

        if (is_object($language)) {
            $language = $language->getCode();
        }
        $translations = $this->getTranslations();


        if (empty($translations[$field][$language])) {
            return false;
        }

        return $translations[$field][$language];
    }

    /**
     * setRateEntity function
     *
     * @access public
     * @param ListModule\Entity\Entity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * getRateEntity function
     *
     * @access public
     * @return ListModule\Entity\Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * getInputFilter function
     *
     * @access public
     * A basic version of this method, so there is always an inputFilter for the model.
     * @return $this->inputFilter
     */
    public function getInputFilter() 
    {
        //echo "function called input filter";
        $inputFilter = new InputFilter();
        
        $this->inputFactory = new InputFactory();

        $inputFilters = $this->getInputFilterConfig($itemFormName);

        foreach ($inputFilters as $keyFilter => $valFilter) {
            $inputFilter->add($this->inputFactory->createInput($valFilter));
        }
      
        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    public function setComponent($component)
    {
        $this->component = $component;
    }

    public function getComponent()
    {
        return empty($this->component) ? $this->getEntity()->getComponent() : $this->component;
    }

    /**
     * getYesNo function
     *
     * @access public
     * @param mixed $flag
     * @return mixed $flag
     */

    public function getYesNo($flag)
    {
        switch ($flag) {
            case 1:
                return static::YES_LABEL;
            case 0:
                return static::NO_LABEL;
            default:
                return 'N/A';
        }
    }

    public function exchangeArray($loadArray = array())
    {
        $this->exchangeArrayTranslations($loadArray);

        if (isset($loadArray['component'])) {
            $this->setComponent($loadArray['component']);
        }

        parent::exchangeArray($loadArray);

    }

    public function exchangeArrayTranslations($loadArray)
    {
        $languages = $this->getLanguages();
        $defaultCode = $languages->getDefaultLanguage()->getCode();

        $translations = $this->getTranslations();

        foreach ($this->getFields() as $valField) {
            if ($valField->getTranslate() == 1) {
                $defaultFieldName = $valField->getName() . '_' . $defaultCode;

                foreach (array_keys($languages->getLanguageOptions()) as $valOption) {
                    $fieldName = $valField->getName() . '_' . $valOption;
                    if ($loadArray['currentLanguage'] == $valOption) {
                        if ($defaultCode != $valOption) {
                            $translations[$valField->getName()][$valOption] = $loadArray[$valField->getName()];
                        }

                    } else {
                        $translations[$valField->getName()][$valOption] = $loadArray[$fieldName];
                    }
                }

                if (!empty($loadArray[$defaultFieldName]) && $loadArray['currentLanguage'] != $defaultCode) {
                    $loadArray[$valField->getName()] = $loadArray[$defaultFieldName];
                }
            }
        }

        return $loadArray;
    }

    public function getArrayCopy($datesToString=false)
    {
        $itemArray = parent::getArrayCopy($datesToString);

        $translations = $this->getTranslations();

        $languages = $this->getLanguages();

        if (empty($languages)) {
            return $itemArray;
        }

        if (is_object($this->getLanguages()->getDefaultLanguage())) {
            $defaultLanguageCode = $this->getLanguages()->getDefaultLanguage()->getCode();            
        } else {
            $defaultLanguageCode = 'en';
        }

        if (is_object($this->getCurrentLanguage)) {
            $currentLanguageCode = $this->getCurrentLanguage()->getCode();
        } else {
            $currentLanguageCode = 'en';
        }

        $languageCodes = array();

        $fields = $this->getFields();

        if (!empty($fields)) {
            foreach ($fields as $valField) {
                if ($valField->getTranslate() == 1) {
                    foreach ($this->getLanguages()->getLanguageCodes() as $valLanguage) {
                        if ($valLanguage != $defaultLanguageCode) {
                            $itemArray[$valField->getName() . '_' . $valLanguage] = $translations[$valField->getName()][$valLanguage];
                        } else {
                            $itemArray[$valField->getName() . '_' . $valLanguage] = $itemArray[$valField->getName()];
                        }

                        if ($valLanguage == $currentLanguageCode && $valLanguage != $defaultLanguageCode) {
                            $itemArray[$valField->getName()] = $translations[$valField->getName()][$valLanguage];
                        }
                     }
                }
            }
        }

        return $itemArray;
    }


    /**
     * getItemArray function
     *
     * @access protected
     * @param mixed $method1
     * @param mixed $method2
     * @param mixed $entity
     * @return mixed $out
     */

    protected function getItemArray($method1, $method2, $entity=0)
    {
        if ($entity === null) {
            return array();
        }
        if ($entity === 0) {
            $entity = $this->entity;
        }
        $out = array();
        foreach ($this->entity->$method1() as $val) {
            $out[] = $val->$method2();
        }
        return $out;
    }
    
    public function save()
    {
        parent::save();

        if ($this->skipListItemSave) {
            return;
        }

        $fields = $this->getFields();

        $translations = $this->getTranslations();

        $languages = $this->getLanguages();

        foreach ($this->getLanguageTranslations() as $valLangTrans) {

            if (!is_null($translations[$valLangTrans->getField()->getName()][$valLangTrans->getLanguage()->getCode()])) {
              $valLangTrans->setContent($translations[$valLangTrans->getField()->getName()][$valLangTrans->getLanguage()->getCode()]);
              $valLangTrans->setModifiedUserId($this->getEntity()->getModifiedUserId());
              $valLangTrans->setModified(new \DateTime());
              $this->getDefaultEntityManager()->persist($valLangTrans);
              $this->getDefaultEntityManager()->flush();
            }
            
            unset($translations[$valLangTrans->getField()->getName()][$valLangTrans->getLanguage()->getCode()]);            
        }


        $item = $this->getItem();

        $saveItemSeparate = false;

        if (!is_object($item)) {
            $saveItemSeparate = true;
            $item = $this->getId();
        }

        $saveItemSeparate = (is_object($item)) ? false : true;

        $defaultLanguage = $languages->getDefaultLanguage();

        foreach ($fields as $valField) {
            if ($valField->getTranslate() != 1) {
                continue;
            }

            foreach ($languages->getLanguages() as $valLanguage) {
                if ($valLanguage->getCode() == $defaultLanguage->getCode()){
                    continue;
                }         

                if (!empty($translations[$valField->getName()][$valLanguage->getCode()])) {
                    $languageTranslation = new \Languages\Entity\LanguageTranslations();
                    $languageTranslation->setComponent($this->getComponent());
                    if (!$saveItemSeparate) {
                        $languageTranslation->setItem($item);
                    }
                    $languageTranslation->setField($valField);
                    $languageTranslation->setLanguage($valLanguage->getEntity());
                    $languageTranslation->setStatus(1);
                    $languageTranslation->setContent($translations[$valField->getName()][$valLanguage->getCode()]);
                  //  var_dump($languageTranslation->getContent());
                  //  die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);
                    $languageTranslation->setModifiedUserId($this->getEntity()->getModifiedUserId());
                    $languageTranslation->setCreatedUserId($this->getEntity()->getCreatedUserId());
                    $languageTranslation->setModified($this->getEntity()->getModified());
                    $languageTranslation->setCreated($this->getEntity()->getCreated());
                    $this->getDefaultEntityManager()->persist($languageTranslation);
                    $this->getDefaultEntityManager()->flush();

                    if ($saveItemSeparate) {
                        $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ? where translationId = ?', array($item, $languageTranslation->getId()));
                    }
                }


            }
        }
    }

    /**
     * getMainImage function
     *
     * @access public
     *
     */

    public function getMainImage()
    {
        $result = array();
    }

    /**
     * getParentModule
     *
     * returns the parent module, to be used for media attachments.
     * This was abstracted out into a method so dynamic modules can use media attachments
     * 
     * @return string
     */
    protected function getParentModule()
    {
        $parentModule = get_class($this);
        $parentModule = explode('\\',$parentModule);
        $parentModule = array_shift($parentModule);

        return $parentModule;
    }

    /**
     *  getMediaAttachments function
     *
     * @access public
     * @return mixed $result
     *
     */

    public function getMediaAttachments()
    {
        $result = array();

        $serviceManager = $this->getServiceManager();

        $currentProperty = $serviceManager->get('currentProperty');
        $attachedMediaFiles = $serviceManager->get('phoenix-attachedmediafiles');
        $currentUser = $serviceManager->get('phoenix-users-current');

        if ($currentProperty && $attachedMediaFiles) {
            $parentModule = $this->getParentModule();

            $attachedMediaFiles->setParentInfo(array(
                'parentModule' => $parentModule,
                'parentItemId' => $this->getId(),
                'currentProperty' => $currentProperty,
                'currentUser' => $currentUser
            ));

            $result = $attachedMediaFiles->getFiles();
        }

        return $result;
    }

    /**
     *  publish function
     *
     * @access public
     *
     */

    public function publish()
    {
        $this->entity->setStatus(1);
        $this->defaultEntityManager->persist($this->entity);
        $this->defaultEntityManager->flush();
    }

    /**
     *  archive function
     *
     * @access public
     *
     */

    public function archive()
    {
        if ($this->entity->setStatus() == 1) {
            $this->entity->setStatus(self::ITEM_STATUS_ARCHIVED);
        }

        $this->defaultEntityManager->persist($this->entity);
        $this->defaultEntityManager->flush();
    }

    /**
     *  delete function
     *
     * @access public
     *
     */

    public function delete()
    {
      $this->defaultEntityManager->remove($this->entity);
      $this->defaultEntityManager->flush();
    }

    protected function getInputFilterConfig($itemFormName)
    {
        $config = $this->config;

        $moduleNamespace = $this->getModuleNamespace();

        return $config->get(array('moduleFields', $moduleNamespace, 'inputFilters', get_class($this)), array());
    }        
}
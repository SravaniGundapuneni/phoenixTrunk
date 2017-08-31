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
class UnifiedListItem extends ListItem
{
    protected $dataEntity;
    protected $dataEntityClass;

    public function __call($name, $args) 
    {
        $result = parent::__call($name, $args);

        if ($name == 'getId') {
            return $result;
        }

        if (empty($result) && !empty($this->entity)) {
            $getDataEntity = 'get' . ucfirst($this->dataEntity);
            $dataEntity = $this->entity->$getDataEntity();
            return (!empty($dataEntity)) ? $dataEntity->$name() : null;
        }

        return $result;

        return $this->entity ? $this->entity->$name() : null;
    }

    /**
     * dataToArray
     *
     * This is abstracted out into another method so child classes can use a different method to handle this.
     * The purpose of this is primarily for models with complex (i.e. multiple entity) relationships
     * @param  boolean $datesToString 
     * 
     * @return array
     */
    protected function dataToArray($datesToString = false)
    {
        $out = parent::dataToArray($datesToString);
        
        foreach ($out[$this->dataEntity]->getNames() as $key => $val) {
            if (in_array($key, array('id', 'item', 'createdUserId', 'modifiedUserId', 'created', 'modified', 'userId', 'status', 'property','categoryId'))) {
                continue;
            }
            $entityGetter = 'get' . ucfirst($key);
            $val = $out[$this->dataEntity]->$entityGetter();
            if ($datesToString && stripos(get_class($val), 'date') === 0) {
                $val = $val->format('Y-m-d');
            }
            $out[$key] = $val;
        }

        unset($out[$this->dataEntity]);

        return $out;
    }    
    
    public function exchangeArray($loadArray = array())
    {
        parent::exchangeArray($loadArray);

        $getDataEntity = 'get' . ucfirst($this->dataEntity);
        $setDataEntity = 'set' . ucfirst($this->dataEntity);

        $dataEntity = $this->entity->$getDataEntity();

        if (empty($dataEntity)) {
            $this->entity->$setDataEntity(new $this->dataEntityClass);
        }
        // Sets entity value for each element in the array
        // The entity will only accept values for existing properties
        foreach ($loadArray as $key => $value) {
            $entitySetter = 'set' . ucfirst($key);
            $this->entity->$getDataEntity()->$entitySetter($value);
        }

        $this->entity->$getDataEntity()->setItem($this->entity);
    }    

    public function save()
    {
        //This is because we want to override the stuff inherited from ListModule\ListItem
        $this->skipListItemSave = true;

        parent::save();

        $fields = $this->getFields();

        $translations = $this->getTranslations();

        $languages = $this->getLanguages();

        $languageTranslations = $this->getLanguageTranslations();

        if (!empty($languageTranslations)) {
            foreach ($languageTranslations as $valLangTrans) {
                $valLangTrans->setContent($translations[$valLangTrans->getField()->getName()][$valLangTrans->getLanguage()->getCode()]);
                $valLangTrans->setModifiedUserId($this->getEntity()->getUserId());
                $valLangTrans->setModified(new \DateTime());
                $this->getDefaultEntityManager()->persist($valLangTrans);
                $this->getDefaultEntityManager()->flush();
                unset($translations[$valLangTrans->getField()->getName()][$valLangTrans->getLanguage()->getCode()]);
            }
        }

        $item = $this->getEntity();

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
                    $languageTranslation = new \Languages\Entity\UnifiedLanguageTranslations();
                    $languageTranslation->setComponent($this->getComponent());
                    $languageTranslation->setItem($item);
                    $languageTranslation->setField($valField);
                    $languageTranslation->setLanguage($valLanguage->getEntity());
                    $languageTranslation->setStatus(1);
                    $languageTranslation->setContent($translations[$valField->getName()][$valLanguage->getCode()]);
                    $languageTranslation->setModifiedUserId($this->getEntity()->getCreatedUserId());
                    $languageTranslation->setCreatedUserId($this->getEntity()->getModifiedUserId());
                    $languageTranslation->setModified($this->getEntity()->getModified());
                    $languageTranslation->setCreated($this->getEntity()->getCreated());
                    $this->getDefaultEntityManager()->persist($languageTranslation);
                    $this->getDefaultEntityManager()->flush();
                    
                    $this->defaultEntityManager->getConnection()->executeUpdate('update languages_translations set item = ? where translationId = ?', array($item->getId(), $languageTranslation->getId()));
                }
            }
        }
    }

}
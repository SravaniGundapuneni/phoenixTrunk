<?php
namespace Languages\Service;

use ListModule\Model\ListItem;
use Languages\EventManager\Event as LanguagesEvent;
use Zend\Stdlib\ArrayObject;

class PropertyLanguages extends \ListModule\Service\Lists
{
    protected $entityName = "Languages\Entity\PropertyLanguges";
    protected $modelClass = "\Languages\Model\Language";

    protected $languages = array();
    protected $languageOptions = array();
    protected $languagesSelect;
    protected $orderBy = array('default' => 'DESC');
    protected $defaultLanguage;

    public function getItems($active = false, $showAll = false)
    {
        $properties = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        if (empty($properties)){
            return array();
        }

        $languagesService = $this->getServiceManager()->get('phoenix-languages');

        $defaultLanguage = $languagesService->getDefaultLanguage();

        $propertyLangArray = array();
        foreach ($properties as $valProperty) {
            $propertyLangs = (object) array();
            $propertyLangArray[] = $propertyLangs;
            $propertyLangs->property = $valProperty;

            $propLanguages = $this->getDefaultEntityManager()->getRepository('Languages\Entity\PropertyLanguages')->findBy(array('property' => $valProperty->getId()));

            $defaultCode = $defaultLanguage->getCode();

            if (empty($propLanguages)) {
                $propertyLangs->defaultCode = $defaultCode;
                $propertyLangs->languages = array($defaultLanguage->getCode() => $defaultLanguage->getName());
                continue;
            }

            $languagesArray = array();
            $languageKeys = array();
            foreach ($propLanguages as $valPropLanguage) {
                $languageKeys[] = $valPropLanguage->getLanguageId();

                if ($valPropLanguage->getDefault() == 1) {
                    $defaultId = $valPropLanguage->getLanguageId();
                }
            }

            foreach ($languagesService->getLanguages() as $valLanguage) {
                if (in_array($valLanguage->getId(), $languageKeys)) {
                    $languagesArray[$valLanguage->getCode()] = $valLanguage->getName();
                    if ($valLanguage->getId() == $defaultId) {
                        $defaultCode = $valLanguage->getCode();
                    }
                }
            }
            $propertyLangs->languages = $languagesArray;
            $propertyLangs->defaultCode = $defaultCode;
        }

        return $propertyLangArray;
    }    
}
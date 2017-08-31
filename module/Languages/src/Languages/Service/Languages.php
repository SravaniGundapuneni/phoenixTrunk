<?php
namespace Languages\Service;

use Languages\Model\Language;
use ListModule\Model\ListItem;
use Languages\EventManager\Event as LanguagesEvent;
use Zend\Stdlib\ArrayObject;

class Languages extends \ListModule\Service\Lists
{
    protected $entityName = Language::ENTITY_NAME;
    protected $modelClass = "\Languages\Model\Language";

    protected $languages = array();
    protected $languageOptions = array();
    protected $languageCodes = array();
    protected $languagesSelect;
    protected $orderBy = array('default' => 'DESC');
    protected $defaultLanguage;

    public function getLanguages()
    {
        return $this->languages;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    public function initLanguageOptions()
    {
        if (!$this->languages) {
            $propertyLanguages = $this->getServiceManager()->get('currentProperty')->getPropertyLanguages();
            if (count($propertyLanguages) > 0) {
                $items = array();
                $defaultItem = array();
                foreach ($propertyLanguages as $propertyLanguage) {
                    $item = $this->getItem($propertyLanguage->getLanguageId());

                    if ($item->getDefault() != 1) {
                        $items[] = $item;
                    } else {
                        $defaultItem[] = $item;
                    }
                }

                $this->languages = array_merge($defaultItem, $items);
            } else {
                $this->languages = $this->getItems(true);
            }
        }

        $languageOptions = array();


        foreach ($this->languages as $valLanguage) {
            if ($valLanguage->getDefault() == 1) {
                $this->defaultLanguage = $valLanguage;
            }

            $usableName = $valLanguage->getCode() . '-' . $valLanguage->getName();

            $languageOptions[$valLanguage->getCode()] = $usableName;
            $languageCodes[] = $valLanguage->getCode();
        }

        $this->languageCodes = $languageCodes;
        $this->languageOptions = $languageOptions;
    }

    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    public function setDefaultLanguage($defaultLanguage)
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    public function getLanguageOptions()
    {
        if (empty($this->languageOptions)) {
            $this->initLanguageOptions();
        }

        return $this->languageOptions;
    }

    public function getLanguageCodes()
    {
        if (empty($this->languageCodes)) {
            $this->initLanguageOptions();
        }

        return $this->languageCodes;
    }

    public function getLanguagesSelect()
    {
        if (empty($this->languagesSelect)) {
            $options = array(
                'value_options' => $this->getLanguageOptions(),
                'label' => 'Scope',
                'label_attributes' => array(
                    'class' => 'blockLabel'
                )
            );

            $languagesSelect = new \Zend\Form\Element\Select('languages', $options);        
            $languagesSelect->setAttribute('class', 'stdInputText');
            $languagesSelect->setAttribute('id', 'languageSelect');

            $this->languagesSelect = $languagesSelect;
        }

        return $this->languagesSelect;
    }

    public function getForm($formName, $sl)
    {
        $form = parent::getForm($formName, $sl);

        $uninstalledLanguages = $this->getUninstalledLanguageOptions($form);

        $languages = $form->get('languages');

        $languages->setValueOptions($uninstalledLanguages);

        return $form;
    }

    public function getUninstalledLanguageOptions()
    {
        $installedLanguages = $this->getItems();

        $installedArray = array();

        foreach ($installedLanguages as $valInstalled) {
            $installedArray[] = $valInstalled->getCode();
        }

        $availableLanguages = $this->getAdminEntityManager()->getRepository('Languages\Entity\Admin\Languages')->findBy(array('status' => 2));

        $uninstalledArray = array();

        $uninstalledArray = $this->getOptionsList($availableLanguages, $installedArray);

        return $uninstalledArray;
    }

    public function getOptionsList($items, $excludeList = array())
    {
        $optionsList = array();

        foreach ($items as $valItem) {
            if (!in_array($valItem->getCode(), $excludeList)) {
                $optionsList[$valItem->getCode()] = $valItem->getName() . " ({$valItem->getEnglishName()})";
            }
        }

        return $optionsList;
    }

    public function save($model, $data)
    {
        if (empty($data['languages']) || !is_array($data['languages'])) {
            return;
        }

        $languages = $data['languages'];

        foreach ($languages as $valLanguage) {
            $adminLanguage = $this->getAdminEntityManager()->getRepository('Languages\Entity\Admin\Languages')->findOneBy(array('code' => $valLanguage));
            
            if (empty($adminLanguage)) {
                continue;
            }

            $adminData = $adminLanguage->toArray();
            unset($adminData['createUserId']);
            unset($adminData['created']);
            unset($adminData['modifiedUserId']);
            unset($adminData['modified']);
            $adminData['status'] = 1;
            unset($adminData['id']);

            $siteLanguage = $this->createModel();

            $siteLanguage->exchangeArray($adminData);

            $siteLanguage->save();
        }
    }

    public function exportTranslations()
    {
        $exportArray = new ArrayObject();

        $this->getEventManager()->trigger(LanguagesEvent::EVENT_EXPORT, 'Languages\EventManager\Event', array('exportArray' => $exportArray));

        return $exportArray;
    }

    public function importTranslations($importText = array())
    {
        $this->getEventManager()->trigger(LanguagesEvent::EVENT_IMPORT, 'Languages\EventManager\Event', array('importText' => $importText));
    }
}
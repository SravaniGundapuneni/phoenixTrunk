<?php

namespace Pages\Service;

use Pages\Model\Page as PagesModel;
use Pages\EventManager\Event as PagesEvent;
use Blocks\Entity;
use Users\Model\User;
use Doctrine\ORM\Query\ResultSetMapping;
use ListModule\Service\UnifiedLists;

class Pages extends UnifiedLists 
{
    protected $entityName;
    protected $categories;
    protected $dataSection = '';

    public function __construct() {
        $this->entityName = PagesModel::ENTITY_NAME;
    }

    public function getDataSection() {
        return $this->dataSection;
    }

    public function setDataSection($dataSection) {
        $this->dataSection = $dataSection;
    }

    public function getPage($id) {
        $r = $this->getDefaultEntityManager()->createQueryBuilder()
                ->select('pg')->from('Pages\Entity\Pages', 'pg')->where("pg.pageId=$id")
                ->getQuery()
                ->getResult();
        return count($r) ? $r[0] : null;
    }

    public function getPageBlocks($blockIds) 
    {
        $in = implode(',', $blockIds);
        return $this->getDefaultEntityManager()
                        ->createQueryBuilder()
                        ->select('bl')->from('Blocks\Entity\Blocks', 'bl')->where("bl.id IN ($in)")
                        ->getQuery()
                        ->getResult();
    }

    public function getPagesByDataSection($entityManager, $entityName, $active = false) 
    {
        $qbPages = $entityManager->createQueryBuilder();
        $qbPages->select('p')
                ->from($entityName, 'p')
                ->leftJoin('p.pagesData', 'pd');

        if ($active == true) {
            $qbPages->where('p.status = 1');
        } else {
            $qbPages->where('p.status >= 0');
        }

        $qbPages->andWhere($qbPages->expr()->like('pd.dataSection', ':dataSection'))
                ->setParameter('dataSection', $this->getDataSection() . '%');

        $result = $qbPages->getQuery()->getResult();

        return (empty($result)) ? array() : $result;
    }

    public function getItem($itemId) 
    {
        $pageEntity = $this->loadPageById($itemId);

        if ($pageEntity) {
            return $this->createModel($pageEntity);
        }

        return false;
    }

    public function getItemByPageKey($pageKey) 
    {
        $now = date('Y-m-d H:i:s');
        $qbPage = $this->defaultEntityManager->createQueryBuilder();
        $qbPage->select('p')
                ->from('Pages\Entity\Pages', 'p')
                ->where('p.pageKey = :pageKey')
                ->andWhere('p.status = :status')
                ->setParameter('pageKey', $pageKey)
                ->setParameter('status', '1');

        $result = $qbPage->getQuery()->getResult();
        $result = (!empty($result)) ? $result[0] : false;

        if ($result) {
            return $this->createModel($result);
        }

        return false;
    }

    public function setCategories($categories) 
    {
        $this->categories = $categories;
    }

    public function loadPage($pageKey, $dataSection = '') 
    {
        $pageDb = $this->loadPageFromDb($pageKey, $dataSection);

        //Load page from config, if that is found.
        $pageConfig = $this->loadPageFromConfig($pageKey, $dataSection);

        if ($pageDb || $pageConfig) {

            $mergedPageConfig = new \Config\Model\MergedConfig($pageConfig);

            $page = $this->createModel($pageDb);
            $page->setPageConfig($mergedPageConfig);
            return $page;
        }

        //If page is not found, return null
        return null;
    }

    public function loadPageById($itemId) 
    {
        $now = date('Y-m-d H:i:s');
        $qbPage = $this->defaultEntityManager->createQueryBuilder();
        $qbPage->select('p')
                ->from('Pages\Entity\PagesItems', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $itemId);

        $result = $qbPage->getQuery()->getResult();


        return (!empty($result)) ? $result[0] : false;
    }

    public function loadPageFromDb($pageKey, $dataSection = '') 
    {
        $qbPage = $this->defaultEntityManager->createQueryBuilder();
        $now = date('Y-m-d H:i:s');
        $qbPage->select('p')
                ->from('Pages\Entity\PagesItems', 'p')
                ->leftJoin('p.pagesData', 'pd')
                ->where('p.status = 1')
                ->andWhere('pd.dataSection = :dataSection')
                ->andWhere('pd.pageKey = :pageKey')
                ->andWhere($qbPage->expr()->orX(
                                $qbPage->expr()->isNull('pd.startDate'), $qbPage->expr()->lte('pd.startDate', ':now')))
                ->andWhere($qbPage->expr()->orX(
                                $qbPage->expr()->isNull('pd.autoExpire'), $qbPage->expr()->gte('pd.autoExpire', ':now')))
                ->setParameter('pageKey', $pageKey)
                ->setParameter('dataSection', $dataSection)
                ->setParameter('now', $now);

        $queryPage = $qbPage->getQuery();
        $resultCacheKey = str_replace('/', '', $pageKey . '-' . $dataSection);

        $result = $queryPage->getResult();

        return (!empty($result)) ? $result[0] : false;
    }

    public function createModel($pageEntity = false) 
    {
        $page = new \Pages\Model\Page($this->getConfig());

        if ($pageEntity) {
            $page->setEntity($pageEntity);
        }

        $page->setDefaultEntityManager($this->defaultEntityManager);
        $page->setEventManager($this->getEventManager());

        if ($this->getServiceManager()->has('currentLanguage')) {
            $page->setCurrentLanguage($this->getServiceManager()->get('currentLanguage'));

            if ($this->getServiceManager()->has('MergedConfig')) {
                $page->setLanguages($this->getServiceManager()->get('phoenix-languages'));
            }
        }

        return $page;
    }

    /**
     * onConfigMerge
     *
     * Triggered by the configMerge Phoenix Event.
     * @param  \Phoenix\EventManager\Event $e The Phoenix Event
     * @return void
     */
    public function onConfigMerge($e) 
    {
        //Set the config for this object to the MergedConfig object.
        //Because PHP 5 objects are by reference, all changes made after this trigger is called will
        //still be applied to this object.
        $this->setConfig($e->getParam('configManager')->getMergedConfig());
    }

    public function save($itemModel, $itemData, $approved = false, $moduleName = null) 
    {
        $module = $this->getDefaultEntityManager()->getRepository('Toolbox\Entity\Components')->findOneBy(array('name' => $this->getModuleName()));

        if (!$itemModel instanceof PagesModel) {
            $itemModel = $this->createModel();
        }

        if ($itemData['template'] == '') {
            $itemData['template'] = 'page.phtml';
        }
        if ((!$approved) && $module && $module->getCasAllowed() && $module->getCasEnabled()) {
            if (!$itemModel->getId()) {
                $this->createCasEntry('save', $itemData);
            } else {
                if ($this->hasChanges($itemModel, $itemData)) {
                    $this->createCasEntry('update', $itemData, $itemModel->getId(), $itemModel->toArray());
                }
            }
        } else {
            if (!isset($itemdData->component)) {
                $itemData['component'] = $this->getModule();
            }

            $itemModel->exchangeArray($itemData);
            $itemModel->save();
            $this->getEventManager()->trigger(PagesEvent::EVENT_PAGE_SAVE, '\Pages\EventManager\Event', array('itemData' => $itemData, 'pageId' => $itemModel->getId()));
        }
    }

    public function trash($items) 
    {
        $this->updateItemsStatuses('Pages\Entity\PagesItems', 'pa', PagesModel::ITEM_STATUS_TRASHED, $items);
    }

    public function archive($items) 
    {
        $this->updateItemsStatuses('Pages\Entity\PagesItems', 'pa', PagesModel::ITEM_STATUS_ARCHIVED, $items);
    }

    public function publish($items) 
    {
        $this->updateItemsStatuses('Pages\Entity\PagesItems', 'pa', PagesModel::ITEM_STATUS_PUBLISHED, $items);
    }

    public function getForm($formName, $serviceManager) 
    {
        $form = parent::getForm($formName, $serviceManager);

        $categoryField = $this->categories->getCategoriesField();

        if ($categoryField) {
            $form->add($categoryField);
        }

        $this->getEventManager()->trigger(PagesEvent::EVENT_PAGE_EDITITEM, '\Pages\EventManager\Event', array('pagesForm' => $form));

        return $form;
    }

    /**
     * getHotelOptions
     *
     * @todo  Remove this from this service class, so the PhoenixPropeties module will be properly decoupled from Pages.
     * 
     * @return array
     */
    public function getHotelOptions() 
    {
        $options = array();

        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }

        return $options;
    }

    // Sravani: Added Template Names from Phoenix Templates module
    public function getTemplateOptions() 
    {
        $templateID = $this->getConfig()->get(array('templateVars', 'templateID'));
        $options = array();
        $siteroot = $this->getConfig()->get(array('templateVars', 'siteroot'));
        $dir = str_replace("\\", "/", SITE_PATH) . "/templates/main/pages";
        $files = scandir($dir, 0);
        for ($i = 2; $i < count($files); $i++) {
            if ($files[$i] == '.svn') {
                continue;
            }
            $options[$files[$i]] = str_replace(".phtml", "", $files[$i]);
        }
        return $options;
    }

    public function getTranslatedBlocks($currentPageId) 
    {
        $languageTranslations = $this->getDefaultEntityManager()->getRepository('Languages\Entity\LanguageTranslations')
                ->findBy(array('component' => 1793, 'item' => $currentPageId));
//        $qbItems = $this->getDefaultEntityManager()->createQueryBuilder();
//        $qbItems->select('dlm')
//                ->from('Toolbox\Entity\Components', 'dlm')
//                ->leftJoin('dlm.componentFields', 'mi')
//                ->leftJoin('dlm.languageTranslations', 'lt')
//                ->where('dlm.name = :moduleName')
//                ->andWhere(
//                        $qbItems->expr()->orx(
//                                'mi.id = lt.item', 'lt.item is NULL'
//                        )
//                )
//                ->setParameter('moduleName', $this->moduleName);
//        $result = $qbItems->getQuery()->getResult();
        if (!empty($languageTranslations)) {

            return ($languageTranslations[0]->getContent());
        }
    }

    protected function loadPageFromConfig($page, $dataSection) 
    {
        $pageConfigPath = SITE_PATH;

        if ($dataSection) {
            if (strpos('/', $dataSection) === false) {
                $pageConfigPath .= '/';
            }
            $pageConfigPath .= $dataSection;
        }

        $pageConfigPath .= "/$page.php";

        // print_r('<pre>');
        // print_r($pageConfigPath);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        if (file_exists($pageConfigPath)) {
            return include $pageConfigPath;
        }

        return array();
    }

    protected function getItemsResult($entityManager, $entityName, $active = false) 
    {
        if (!$this->getCurrentUser() instanceof User || $this->getCurrentUser()->isCorporate()) {
            return parent::getItemsResult($entityManager, $entityName, $active);
        }

        return $this->getPagesByDataSection($entityManager, $entityName, $active);
    }
}

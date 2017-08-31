<?php
/**
 * The Categories Service File
 *
 * This file contains the Categories Class, which is service class for ListModule Categories 
 * 
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace ListModule\Service;

use \ListModule\Model\Category;

/**
 * The Categories Service Class
 * 
 * @category    Toolbox
 * @package     ListModule
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      A. Tate <atate@travelclick.com>
 */
class Categories extends \ListModule\Service\Lists
{
    protected $entityName = 'ListModule\Entity\Categories';
    protected $property;
    protected $moduleName;

    public function __construct()
    {
        $this->modelClass = "\ListModule\Model\Categories";
    }
    /**
     * setCurrentUser function
     *
     * Setter for $this->currentUser
     * @access public
     * @param Users\Model\User $currentUser
     *
     */
    /**
     * getForm
     * 
     * @param  string $formName
     * @param  mixed $sl
     * @return DynamicListModule\Form\FieldForm
     */
    public function getForm($formName, $sl = null)
    {
        $formName = '\ListModule\Form\CategoryForm';

        return parent::getForm($formName, $sl);
    }
    
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * setProperty function
     *
     * Setter for $this->property
     * @access public
     * @param mixed $property
     *
     */

    public function setProperty($property)
    {
        $this->property = $property;
    }

    /**
     * setModuleName function
     *
     * Setter for $this->moduleName
     * @access public
     * @param mixed $moduleName
     *
     */

    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * setServiceManager function
     *
     * Setter for $this->serviceManager
     * @access public
     * @param mixed $serviceManager
     *
     */

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * getItemsBy function
     *
     * @access public
     * @param array $filters
     * @return mixed $items
     *
     */


    public function getItemsBy(array $filters = array(), $orderBy = array())
    {
        $entityManager = $this->getDefaultEntityManager();
        $entityRepository = $entityManager->getRepository($this->entityName);
        $results = $entityRepository->findBy(array());

        if (!$results) {
            return array();
        }

        foreach($results as $valItem) {
            $items[] = $this->createModel($valItem);
        }

        return $items;
    }

    /**
     * createModel function
     *
     * @access public
     * @param mixed $entity
     * @return mixed $model
     *
     */

    public function createModel($entity=false)
    {
        $modelClass = 'ListModule\Model\Category';
        $model = new $modelClass($this->getConfig());
        $model->setDefaultEntityManager($this->getDefaultEntityManager());
        $model->setAdminEntityManager($this->getAdminEntityManager());
        $model->setCurrentUser($this->currentUser);
        $model->setLanguages($this->getServiceManager()->get('phoenix-languages'));

        if ($entity) {
            $model->setEntity($entity);
        }
        return $model;
    }

    /**
     * getCategoriesField function
     *
     * @access public
     * @return mixed false
     *
     */

    public function getCategoriesField()
    {
        if ( is_object($this->property) )
        {
            $categoryItems = $this->getItemsBy(array('propertyId' => $this->property->getId(), 'module' => $this->moduleName));

            if (empty($categoryItems)) {
                return false;
            }

            $options = array();

            foreach ($categoryItems as $valItem) {
                $options[$valItem->getId()] = $valItem->getName();
            }

            $formField = new \Zend\Form\Element\Select('categoryId');
            $formField->setLabel('Category');
            $formField->setValueOptions($options);
            $formField->setLabelAttributes(array('class' => 'blockLabel'));
            $formField->setAttribute('class', 'stdTextInput');

            return $formField;
        }

        return false;
    }
    
    public function getItems($active = false, $showAll = false)
    {
        
        $items = array();
        if (!($results = $this->getItemsResult($this->getDefaultEntityManager(), $this->entityName, $active))) {
            return array();
        }
        foreach ($results as $valItem) {
            if ($valItem->getModule() == $this->moduleName) 
            {
            //echo $valItem->getModule()->getId();exit;
                $items[] = $this->createModel($valItem);
            }
        }
		//var_dump($items);
        return $items;
    }
    
    public function save($model, $data)
    {
        if ( ! $model ) {
            $model = $this->createModel();
        }
        $data['module']=$this->moduleName;
        $model->loadFromArray($data);
        $model->save();
    }  
}
<?php

/**
 * The PhoenixRates Service
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace PhoenixRates\Service;

use PhoenixRates\Model\Rate;
use PhoenixRates\Entity\PhoenixRate;
use PhoenixProperties\Service\SubmoduleUnifiedAbstract;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class Rates extends SubmoduleUnifiedAbstract
{
	public $pagination = true;
	public $paginator;
	public $currentPage = 1;
	public $listSearch = '';

	protected $orderList = true;
	/**
	 * __construct
	 *
	 * Construct our rates service
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->entityName = Rate::ENTITY_NAME;
		$this->modelClass = "\PhoenixRates\Model\Rate";
        $this->dataItem = Rate::RATE_DATA_ENTITY;		
	}

	/**
	 * This method is called from the parent class and adds ordering by 'featured' field to the query
	 * @param type $qb
	 */
	protected function modifyQuery(&$qb)
	{
		$qb->orderBy('pp.featured DESC, pp.property ASC, pp.name ASC');
	}

	/**
	 * getRate
	 *
	 * An alias of getItem
	 *
	 * @param  integer $selector
	 * @return \PhoenixRates\Model\Rate
	 */
	public function getRate($selector)
	{
		$result = null;

		if (is_numeric($selector) && intval($selector)) {
			$result = $this->getItem($selector);
		} elseif (is_string($selector) && strlen($selector)) {
			$result = $this->getItemBy(array('code' => $selector));
		} elseif (is_array($selector) && $selector) {
			$result = $this->getItemBy($selector);
		}

		return $result;
	}

	public function getDailyRate($options)
	{
		$startDate = $options['startDate'];
		$endDate   = $options['endDate'];

		$query = "
			SELECT
				price,
				bookingLink
			FROM
				phoenixrates
			WHERE
				price =
				(
					SELECT
						MIN(price)
					FROM
						phoenixrates
					WHERE
						startDate <= '" . $startDate . "'
					AND
						autoExpiry <= '" . $endDate . "'
				)
			LIMIT
				1";

		// TODO: Doctrinify this
		$result = $this->getDefaultEntityManager()->getConnection()->fetchAll($query);

		return $result[0];
	}

	public function getPageRates($pageId)
	{
		$qry = $this->getDefaultEntityManager()->createQueryBuilder()
				->select('pr')
				->from('PhoenixRates\Entity\PageRates', 'pr')
				->where("pr.pageId=:pageId")
				->orderBy('pr.orderNumber', 'ASC')
				->setParameter('pageId', $pageId)
				->getQuery();

		return $qry->getResult();
	}
	
	public function getCadCurrency()
	{
		$em = $this->getAdminEntityManager()->createQueryBuilder();
		$em->select('u')->from('PhoenixRates\Entity\Admin\BaseCurrency', 'u')->where("u.currency=:pageId")->setParameter('pageId', 'CAD');
		$result = $em->getQuery()->getResult();

		return $result;
	}
	
	 public function getCurrencyConversion($amount = 0, $fromCurrency = 'USD', $toCurrency = 'USD')
	 {
		if ($fromCurrency == 'USD') 
		{
			return $amount;  
		}
		 
		$em = $this->getAdminEntityManager()->createQueryBuilder();
		$em->select('u')->from('PhoenixRates\Entity\Admin\BaseCurrency', 'u')->where("u.currency=:pageId")->setParameter('pageId', $fromCurrency);
		$usdConversion = $em->getQuery()->getResult();
		$usdAmount = $amount / $usdConversion;
		
		if ($toCurrency == 'USD') 
		{
			return $usdAmount;  
		}
		$em->select('u')->from('PhoenixRates\Entity\Admin\BaseCurrency', 'u')->where("u.currency=:pageId")->setParameter('pageId', $toCurrency);
		$toConversion = $em->getQuery()->getResult();
		return $usdAmount * $toConversion;
		
	}

	public function onPageEditItem($e)
	{
		$rates = $this->getItems();

		if (!$rates) {
			return true;
		}

		$ratesArray = array();

		foreach ($rates as $keyRate => $valRate) {
			$ratesArray[$valRate->getId()] = $valRate->getProperty()->getName() . ': ' . $valRate->getName();
		}

		asort($ratesArray);

		$ratesCheckboxes = new \Phoenix\Form\Element\OrderedSelect('pageRates');
		$ratesCheckboxes->setLabel('Rates for this page');
		$ratesCheckboxes->setValueOptions($ratesArray);
		$ratesCheckboxes->setAttribute('multiple', true);
		$ratesCheckboxes->setAttribute('id', 'pageRates');
		$ratesCheckboxes->setTemplate('phoenix-rates/toolbox/select-ordered-rates');
		$pagesForm = $e->getParam('pagesForm');

		$pagesForm->add($ratesCheckboxes);

		return true;
	}

	public function onPageGetArrayCopy($e)
	{
		$pageArray = $e->getParam('pageArray');
		$pageId = $pageArray->id;

		$pageRates = $this->getPageRates($pageId);

		$pageRatesArray = array();

		foreach ($pageRates as $valRate) {
			$pageRatesArray[] = $valRate->getRateId();
		}

		$pageArray->pageRates = $pageRatesArray;

		return true;
	}

	public function onPageGetInputFilter($e)
	{
		$inputFilter = $e->getParam('inputFilter');

		$factory = new InputFactory();

		$inputFilter->add($factory->createInput(array(
					'name' => 'pageRates',
					'required' => false)
		));
	}

	public function onPageSave($e)
	{
		$entityManager = $this->getDefaultEntityManager();
		$itemData = $e->getParam('itemData');
		$pageId = $e->getParam('pageId');

		if ($itemData['skipRates'] == true) {
			return true;
		}

		$qbDelete = $entityManager->createQueryBuilder();

		$qbDelete->delete('PhoenixRates\Entity\PageRates', 'pr')
				->where('pr.pageId = :pageId')
				->setParameter('pageId', $pageId);

		$qbDelete->getQuery()->execute();

		$pageRates = $itemData['pageRates'];

		if (is_array(($pageRates))) {
			foreach ($pageRates as $keyRate => $valRate) {
				if (!$valRate) {
					continue;
				}
				$orderNumber = (int) $keyRate;
				$orderNumber++;
				$pageRate = new \PhoenixRates\Entity\PageRates();
				$pageRate->setPageId($pageId);
				$pageRate->setRateId($valRate);
				$pageRate->setOrderNumber($orderNumber);

				$entityManager->persist($pageRate);
			}

			$entityManager->flush();
		}
	}

	public function onPageDisplay($e)
	{
		$dynamicContentModel = $e->getParam('dynamicContentModel');
		$page = $e->getParam('page');

		$pageRates = $this->getPageRates($page->getId());

		$ratesToDisplay = array();

		if (is_array($pageRates)) {
			foreach ($pageRates as $valRate) {
				$ratesToDisplay[] = $this->getItem($valRate->getRateId());
			}
		}

		$rateViewModel = new \Zend\View\Model\ViewModel();

		$rateViewModel->setTemplate('phoenix-rates/widget/display-rates');
		$rateViewModel->ratesToDisplay = $ratesToDisplay;

		$dynamicContentModel->addChild($rateViewModel, 'dynamicContent', true);

		return true;
	}

	public function setViewVars($view)
	{
		$rates = $this->getPageRates($GLOBALS['tc_current_page_id']);
		$ratesToDisplay = array();
		if (is_array($rates)) {
			foreach ($rates as $val) {
				$ratesToDisplay[] = $this->getItem($val->getRateId());
			}
		}
		$view->ratesToDisplay = $ratesToDisplay;
	}

	public function dynaPageRates()
	{
		if (!is_array($rates = $this->getPageRates($GLOBALS['tc_current_page_id'])) || !count($rates)) {
			return array();
		}
		$siteroot = $this->getConfig()->get(array('templateVars', 'siteroot'));
		$ratesToDisplay = array();
		$now = new \DateTime();
		foreach ($rates as $val) {
			$expireDate = $val->getAutoExpiry();
			$startDate = $val->getStartDate();

			if ((!is_null($expireDate) && $now > $expireDate) || (!is_null($startDate) && $now < $startDate)) {
				continue;
			}
			$item = $this->getItem($val->getRateId());
			$date = $item->getAutoExpiry();
			$ratesToDisplay[] = array(
				'name' => $item->getName(),
				'description' => $item->getDescription(),
				'expiration' => date_format($item->getAutoExpiry(), 'M. d, Y'),
				'bookingLink' => "{$siteroot}reservations/?htld=LWTPB&rtcd=" . $item->getCode(),
				'photo' => 'images/hotel-9.jpg'
			);
		}

		return $ratesToDisplay;
	}

	public function createRate($data, $save = false)
	{
		$status = Rate::DEFAULT_ITEM_STATUS;
		$entityModel = $this->createModel();
		$entityModel->setEntity(new PhoenixRate);
		$entityModel->getEntity()->setStatus($status);
		$entityModel->loadFromArray($data);
		if ($save)
			$entityModel->save();
		//var_dump($entityModel);
		return $entityModel;
	}

	public function save($model, $data)
	{
		// pack multiple selection array to a string value to save it in one field (for now)
		if (!empty($data['membership']) && is_array($data['membership'])) {
			$data['membership'] = implode(',', $data['membership']);
		} else {
			$data['membership'] = '';
		}

		if (!$model->getUserModified()) {
			$data['userModified'] = $this->hasChanges($model, $data);
		}

		$entity = $model->getEntity();
	   
		if (!$model->getUserModified()) {
			$data['userModified'] = $this->hasChanges($model, $data);
		}

		if (!empty($data['property']) && !is_object($data['property'])) {
			$data['property'] = $this->getServiceManager()->get('phoenix-properties')->getItem($data['property'])->getEntity();
		} else {
			$data['property'] = $this->getServiceManager()->get('currentProperty')->getEntity();
		}

		parent::save($model, $data);
	}




	 /**
	 * getItemsResult
	 *
	 * Returns an array of items from the repository
	 *
	 * @param  object $entityManager
	 * @param  string $entityName
	 * @return array
	 */
	protected function getItemsResult($entityManager, $entityName, $active = false, $showAll = false)
	{
		$orderBy = $this->orderBy;
		$params = array();

		if ($this->getOrderList()) {
			$params=
			$orderBy = array_merge(array('orderNumber' => 'ASC'), $orderBy);
		}

		if ($active) {
			return $entityManager->getRepository($entityName)->findBy(array('status' => 1), $orderBy);
		}

		$qb =  $this->getDefaultEntityManager()->createQueryBuilder();
		$query = $qb
				->select('pr')
				->from('PhoenixRates\Entity\PhoenixRate', 'pr')
				->where($qb->expr()->eq('pr.status', '1'))
				->where('pr.name LIKE :search')
				->orWhere('pr.code LIKE :search')
				->setParameter('search', '%'.$this->listSearch.'%')
				->getQuery();

				
	   $adapter = new DoctrineAdapter(new ORMPaginator($query));
	   $this->paginator = new Paginator($adapter);
	   $this->paginator->setDefaultItemCountPerPage(10);
	   $this->paginator->setCurrentPageNumber($this->currentPage);
	   
	   return $this->paginator;

	}

}

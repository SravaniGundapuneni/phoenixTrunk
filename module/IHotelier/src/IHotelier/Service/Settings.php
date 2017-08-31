<?php
namespace IHotelier\Service;

use IHotelier\Model\Setting as SettingsModel;
use Phoenix\Service\ServiceAbstract;

class Settings extends \ListModule\Service\Lists
{
	const ALPHANUMERIC_SANITIZER = '/[^A-Za-z0-9_]/';
	const SETTING = '\IHotelier\Entity\IHotelierSetting';

	private $defaultSettings = array(
		'bookingChannelType' => '',
		'hotelId'            => '',
		'companyNameCode'    => '',
		'dataSource'         => '',
		'requestorIDID'      => '',
		'requestorIDType'    => '',
		'fallbackRate'       => '0',
		'lookaheadDays'      => '1',
		'messageID'          => '',
		'headerUsername'     => '',
		'headerPassword'     => '',
	);

	public function getSettingValue($settingKey)
	{
		$settingValue = '';
		$em           = $this->getDefaultEntityManager();
		$entity       = $em->getRepository(self::SETTING)->findOneBy(array('key' => $settingKey));

		if (!is_null($entity)) {
			$settingValue = $entity->getValue();	
		}

		return $settingValue;
	}

	public function getSettings()
	{
		$settings = array();
		$em       = $this->getDefaultEntityManager();
		$entities = $em->getRepository(self::SETTING)->findAll();	

		foreach ($entities as $entity) {
			$settings[$entity->getKey()] = $entity->getValue();
		}

		return array_merge($this->defaultSettings, $settings);
	}

	public function saveSettings($options)
	{
		$em = $this->getDefaultEntityManager();

		foreach ($options as $settingKey => $settingValue) {

			$settingKey   = $this->sanitizeAlphaNumeric($settingKey);
			$settingValue = $this->sanitizeAlphaNumeric($settingValue);

			$entity = $em->getRepository(self::SETTING)->findOneBy(array('key' => $settingKey));

			if (is_null($entity)) {
				$this->insertSetting($settingKey, $settingValue);
			} else {
				$this->updateSetting($settingKey, $settingValue);
			}
		}
	}

	private function createSettingsModel($entity)
	{
		if (!$entity) {
			throw new \Exception('Undefined entity');
		}

		$model = new SettingsModel();
		$model->setDefaultEntityManager($this->getDefaultEntityManager());
		$model->setEntity($entity);

		return $model;
	}

	private function insertSetting($settingKey, $settingValue)
	{
		$setting = $this->createSettingsModel(new \IHotelier\Entity\IHotelierSetting());
		$setting->getEntity()->setKey($settingKey);
		$setting->getEntity()->setValue($settingValue);
		$setting->save();	
	}

	private function sanitizeAlphaNumeric($field)
	{
		return preg_replace(self::ALPHANUMERIC_SANITIZER, '', $field);
	}

	private function updateSetting($settingKey, $settingValue)
	{
		$queryBuilder = $this->getDefaultEntityManager()->createQueryBuilder();
		$queryBuilder->update(self::SETTING, 'ihs')
					 ->set('ihs.value', '?1')
					 ->where('ihs.key = ?2')
					 ->setParameter(1, $settingValue)
					 ->setParameter(2, $settingKey)
					 ->getQuery()->execute();
	}
}
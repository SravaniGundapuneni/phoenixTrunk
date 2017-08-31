<?php
namespace Integration\Service;
use Phoenix\Service\ServiceAbstract;

/*
 * Notes 12-13-2014 Jason Bowden
 * Ideally, this should be called in the GetPropertyInformation.php to avoid duplication
 */
class PropertyInformation extends ServiceAbstract
{
	protected $phoenixProperties;

	public function setPhoenixProperties($phoenixProperties)
	{

		$this->phoenixProperties = $phoenixProperties;
	}

	public function getPropertyInfo($propertyId)
	{
		$result = $this->getResutTemplate($propertyId);

		if ($property = $this->phoenixProperties->getProperty($propertyId)) {
			$result['id']   = $property->getId();
			$result['code'] = $property->getCode();
			$result['name'] = $property->getName();

			$isCorporate = $property->getIsCorporate();

			$result['information'] = array(
				'attachments' => $this->getMediaAttachments($property),
				'city'        => $property->getCity(),
				'code'        => $property->getCode(),
				'coordinates' => $property->getLatitude() . ',' . $property->getLongitude(),
				'country'     => $property->getCountry(),
				'email'       => $property->getEmail(),
				'facebook'    => $property->getFacebook(),
				'fax'         => $property->getFaxNumber(),
				'history'     => $property->getHistory(),
				'instagram'   => $property->getInstagram(),
				'intro'       => $property->getDescription(),
				'isCorporate' => ($isCorporate == 1) ? true : false,
				'labelX'      => $property->getLabelX(),
				'labelY'      => $property->getLabelY(),
				'mainImage'   => $this->getMainImage($property),
				'map'         => null,
				'name'        => $property->getName(),
				'phone'       => $property->getPhoneNumber(),
				'policy'      => $property->getPolicy(),
				'resPhone'    => $property->getTollfreeNumber(),
				'sitePath'    => $property->getSitePath(),
				'state'       => $property->getState(),
				'street'      => $property->getAddress(),
				'tempFormat'  => $property->getTempFormat(),
				'twitter'     => $property->getTwitter(),
				'url'         => $property->getUrl(),
				'userId'      => $property->getUserId(),
				'zip'         => $property->getZip(),
			);
		}

		return $result;
	}

	private function getResutTemplate($propertyId)
	{
		return array(
			'id' => $propertyId,
			'code' => null,
			'name' => null,
			'information' => array(),
		);
	}

	private function getMainImage($item)
	{
		$result = null;

		if ($mediaAttachmetns = $this->getMediaAttachments($item)) {
			$result = array_shift($mediaAttachmetns);
		}

		return $result;
	}
	
	protected function getMediaAttachments($item)
	{
		$result = array();

		$attachments = $item->getMediaAttachments();
		
		foreach ($attachments as $key => $attachment)
		{
			$result[] = array(
				'fileId' => $attachment->getFile(),
				'large'  => $attachment->getFilePath(),
				'thumb'  => $attachment->getThumbnail(),
				'title'  => $attachment->getTitle(),
			);
		}

		return $result;
	}
}
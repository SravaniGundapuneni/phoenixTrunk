<?php
namespace MediaManager\Entity;
use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="schema_dot_org_images")
 * @ORM\Entity
 */
class MediaManagerSchemaDotOrg extends \ListModule\Entity\Entity
{

	/**
	 * @var integer id
	 * 
	 * @ORM\Column(name="schemaDotOrgId", type="integer", length=11)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 */
	protected $id;

	/**
	 * @var integer fileId
	 * 
	 * @ORM\Column(name="`fileId`", type="integer", length=11)
	 */
	protected $fileId;
	
	/**
	 * @var text itemprop
	 * 
	 * @ORM\Column(name="`itemprop`", type="string", length=255)
	 */
	protected $itemprop;

	public function getId()
	{
		return $this->id;
	}

	public function getFileId()
	{
		return $this->fileId;
	}

	public function getItemprop()
	{
		return $this->itemprop;
	}

	public function setItemprop($itemprop)
	{
		$this->itemprop = $itemprop;
	}

	public function setFileId($fileId)
	{
		$this->fileId = $fileId;
	}
}

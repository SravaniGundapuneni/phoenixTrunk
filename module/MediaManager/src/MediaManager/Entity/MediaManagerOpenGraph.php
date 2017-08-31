<?php
namespace MediaManager\Entity;
use \Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="open_graph")
 * @ORM\Entity
 */
class MediaManagerOpenGraph extends \ListModule\Entity\Entity
{

	/**
	 * @var integer id
	 * 
	 * @ORM\Column(name="openGraphId", type="integer", length=11)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 *
	 */
	protected $id;

	/**
	 * @var integer fileid
	 * 
	 * @ORM\Column(name="`fileid`", type="integer", length=11)
	 */
	protected $fileid;
	
	/**
	 * @var text ogproperty
	 * 
	 * @ORM\Column(name="`ogproperty`", type="string", length=255)
	 */
	protected $ogproperty;

	/**
	 * @var text content
	 * 
	 * @ORM\Column(name="`content`", type="string", length=255)
	 */
	protected $content;

	public function getId()
	{
		return $this->id;
	}

	public function getFileid()
	{
		return $this->fileid;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getOGProperty()
	{
		return $this->ogproperty;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}	

	public function setOGProperty($ogproperty)
	{
		$this->ogproperty = $ogproperty;
	}

	public function setFileid($fileid)
	{
		$this->fileid = $fileid;
	}
}

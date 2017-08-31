<?php
namespace IHotelier\Entity;

use \Doctrine\ORM\Mapping as ORM;
use Phoenix\Module\Entity\EntityAbstract;
/**
 * IHotelierSetting
 *
 * @ORM\Table(name="ihotelier_settings")
 * @ORM\Entity
 */
class IHotelierSetting extends EntityAbstract
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    protected $id;

    /**
     * @var string key
     *
     * @ORM\Column(name="`key`", type="string")
     */
    protected $key;

    /**
     * @var string value
     *
     * @ORM\Column(name="`value`", type="string")
     */
    protected $value;

    public function getId()
    {
        return $this->id;
    }

    /**
     * getKey
     * 
     * Getter for the key property
     * @return string key
     *
     */

    public function getKey()
    {
        return $this->key;
    }

    /**
     * getValue
     * 
     * Getter for the value property
     * @return string value
     *
     */

    public function getValue()
    {
        return $this->value;
    }

    /**
     * setId
     * 
     * Setter for the id property
     * @param integer id
     *
     */

    public function setId($id)     
    {
        $this->id = $id;
    }

    /**
     * setKey
     * 
     * Setter for the key property
     * @param string key
     *
     */

    public function setKey($key)     
    {
        $this->key = $key;
    }

    /**
     * setValue
     * 
     * Setter for the value property
     * @param string value
     *
     */

    public function setValue($value)     
    {
        $this->value = $value;
    }

    

}
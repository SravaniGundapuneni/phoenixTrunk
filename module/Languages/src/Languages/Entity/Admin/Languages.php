<?php
/**
 * Languages Entity file
 *
 * @category        Toobox
 * @package         Languages
 * @subpackage      Enttites
 * @copyright       Copyright (c) 2013 Travelclick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.5.5
 * @since           File available since release 13.5.5
 * @author          Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */
namespace Languages\Entity\Admin;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * @ORM\Table(name="languages")
  * @ORM\Entity
  */
class Languages extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="langID", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string code
     *
     * @ORM\Column(name="code", type="string", length=4)
     */
    protected $code;

    /**
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length=48)
     */
    protected $name;

    /**
     * @var string name
     *
     * @ORM\Column(name="nameEnglish", type="string", length=255)
     */
    protected $englishName;

    /**
     * @var integer $userId
     *
     * @ORM\Column(name="createdUserId", type="integer", length=11)
     */
    protected $createdUserId;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;


    /**
     * @var integer $userId
     *
     * @ORM\Column(name="modifiedUserId", type="integer", length=11)
     */
    protected $modifiedUserId;

    /**
     * @var datetime modified
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    protected $modified;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", length=1)
     */
    protected $status;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }    
}
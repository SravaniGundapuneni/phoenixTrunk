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
namespace Languages\Entity;

use \Doctrine\ORM\Mapping as ORM;

 /**
  * @ORM\Table(name="languages_translations")
  * @ORM\Entity
  */
class UnifiedLanguageTranslations extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="translationId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string code
     *
     * @ORM\Column(name="content", type="string")
     */
    protected $content;

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

    /**
     * @var Toolbox\Entity\Components $component
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\Components", inversedBy="languageTranslations")
     * @ORM\JoinColumn(name="component", referencedColumnName="componentId")
     */
    protected $component;

    /**
     * @var Toolbox\Entity\ComponentFields $field
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\ComponentFields", inversedBy="languageTranslations")
     * @ORM\JoinColumn(name="field", referencedColumnName="componentFieldId")
     */
    protected $field;

    /**
     * @var Toolbox\Entity\ComponentItems $item
     *
     * @ORM\ManyToOne(targetEntity="Toolbox\Entity\ComponentItems", inversedBy="languageTranslations")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;

    /**
     * @var Languages\Entity\Languages $language
     *
     * @ORM\ManyToOne(targetEntity="Languages\Entity\Languages", inversedBy="languageTranslations")
     * @ORM\JoinColumn(name="language", referencedColumnName="languageId")
     */
    protected $language;
}
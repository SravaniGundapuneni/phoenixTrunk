<?php
/**
 * SeoMetaText Entity file
 *
 * Module Tables Config
 *
 *     SchemaHelper::primary('attributeId'),
 *     SchemaHelper::varchar('name'),
 *     SchemaHelper::text('description'),
 *     SchemaHelper::tinyint('categories'),
 *     SchemaHelper::int('userId'),
 *     SchemaHelper::datetime('created'),
 *     SchemaHelper::datetime('modified'),
 *     SchemaHelper::int('status'),
 *     SchemaHelper::int('pageId'),
 *     SchemaHelper::varchar('url')
 *
 * @category        Toolbox
 * @package         SeoMetaText
 * @subpackage      Entities
 * @copyright       Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license         All Rights Reserved
 * @version         Release: 13.6
 * @since           File available since release 13.6
 * @author          A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace SeoMetaText\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * SeoMetaText
 *
 * @ORM\Table(name="seoMetaText")
 * @ORM\Entity
 */
class SeoMetaText extends \ListModule\Entity\Entity
{
    /**
     * @var integer id
     *
     * @ORM\Column(name="seoMetaId", type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string metaH1
     *
     * @ORM\Column(name="metaH1", type="string", length=255)
     */
    protected $metaH1;

    
    /**
     * @var string metaTitle
     *
     * @ORM\Column(name="metaTitle", type="string", length=255)
     */
    protected $metaTitle;
    
    /**
     * @var string metaCanonical
     *
     * @ORM\Column(name="metaCanonical", type="string", length=255)
     */
    protected $metaCanonical;


    /**
     * @var string metaDescription
     *
     * @ORM\Column(name="metaDescription", type="string" , length=255)
     */
    protected $metaDescription;

  
    /**
     * @var integer $createdUserId
     *
     * @ORM\Column(name="`createdUserId`", type="integer", length=11)
     */
    protected $createdUserId;
    /**
     * @var integer $modifiedUserId
     *
     * @ORM\Column(name="`modifiedUserId`", type="integer", length=11)
     */
    protected $modifiedUserId;

    /**
     * @var datetime created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var integer status
     *
     * @ORM\Column(name="status", type="integer", length=1)
     */
    protected $status;
    /**
     * @var integer pageId
     *
     * @ORM\Column(name="pageId", type="integer", length=11)
     */
    protected $pageId;
  
    /**
     * @var SeoMetaText\Entity\SeoMetaTextItems $item
     *
     * @ORM\OneToOne(targetEntity="SeoMetaText\Entity\SeoMetaTextItems", inversedBy="seoMetaTextData", cascade="persist")
     * @ORM\JoinColumn(name="item", referencedColumnName="itemId")
     */
    protected $item;  
}
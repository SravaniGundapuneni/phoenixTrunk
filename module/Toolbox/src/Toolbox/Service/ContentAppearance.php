<?php
/**
 * Content Appearance
 *
 * Content Appearance Tracking functionality
 *
 * @category    Toolbox
 * @package     Toolbox
 * @subpackage  ContentAppearance
 * @copyright   Copyright (c) 2011 EZYield.com, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */

namespace Toolbox\Service;

use Phoenix\Service\ServiceAbstract;
use Phoenix\EventManager\Event as PhoenixEvent;

/**
 * Content Appearance
 *
 * Content Appearance Tracking functionality
 *
 * @category    Toolbox
 * @package     Toolbox
 * @subpackage  ContentAppearance
 * @copyright   Copyright (c) 2011 EZYield.com, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5.5
 * @since       File available since release 13.5.5
 * @author      Jose A Duarte <jduarte@travelclick.com>
 */

class ContentAppearance extends ServiceAbstract
{
    protected $appearances = array();

    /**
     * Add content to the appearances array
     *
     * @param string $contentType
     * @param string $contentKey
     * @param array  $configuration [description]
     */
    public function add($contentType, $contentKey, $configuration = array())
    {
        $this->appearances[] = array(
            'contentType' => $contentType,
            'contentKey' => $contentKey,
            'configuration' => $configuration
        );
    }

    /**
     * Commits the recorded content appearances of the page
     *
     * @param  Pages\Model\Page $page
     * @return void
     */
    public function save($page)
    {
        $pageKey = $page->getPageKey();

        /**
         * Adding this so that we dont end up with duplicates
         * in the contentAppearance table
         */
        $this->appearances = array_unique($this->appearances,SORT_REGULAR);

        /**
         * TODO: We need to enhance this functionality
         */
        foreach ($this->appearances as $key => $appearance)
        {
            $contentType = $appearance['contentType'];
            $contentKey  = $appearance['contentKey'];

            if ( $this->find($pageKey, $contentType, $contentKey) )
            {
                $this->update($pageKey, $contentType, $contentKey);
            }
            else
            {
                $this->insert($pageKey, $contentType, $contentKey);
            }
        }
    }

    /**
     * Checks wether a given content appearance already exists for this page
     *
     * @param  string $pageKey
     * @param  string $contentType [description]
     * @param  string $contentKey  [description]
     * @return bool
     */
    protected function find($pageKey, $contentType, $contentKey)
    {
        $query = $this->defaultEntityManager->getConnection()->executeQuery(
            "SELECT * FROM contentAppearance WHERE contentType = ? AND contentKey = ? AND page = ?",
            array(
                $contentType,
                $contentKey,
                $pageKey,
            )
        );

        return $query->fetch();
    }

    /**
     * Updates the modified column for the given content appearance
     *
     * @param  string $pageKey
     * @param  string $contentType
     * @param  string $contentKey
     * @return bool
     */
    protected function update($pageKey, $contentType, $contentKey)
    {
        return $this->defaultEntityManager->getConnection()->update(
            'contentAppearance',
            array(
                'modified'      => date('Y-m-d H:i:s'),
            ),
            array(
                'contentType'   => $contentType,
                'contentKey'    => $contentKey,
                'page'          => $pageKey,
            )
        );
    }

    /**
     * Inserts the given content appearance to the database
     *
     * @param  string $pageKey
     * @param  string $contentType
     * @param  string $contentKey
     * @return bool
     */
    protected function insert($pageKey, $contentType, $contentKey)
    {
        return $this->defaultEntityManager->getConnection()->insert(
            'contentAppearance',
            array(
                'appearanceId'  => 0,
                'created'       => date('Y-m-d H:i:s'),
                'modified'      => date('Y-m-d H:i:s'),
                'contentType'   => $contentType,
                'contentKey'    => $contentKey,
                'page'          => $pageKey,
            )
        );
    }
}
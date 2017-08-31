<?php
/**
 * Legacy Condor Content Appearance Tracking Adapter
 *
 * This is the legacy adapter that corresponds to the condor ContentAppearanceTracking class.
 *
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

/**
 * Legacy Condor Content Appearance Tracking Adapter
 *
 * This is the legacy adapter that corresponds to the condor ContentAppearanceTracking class.
 * 
 * Unlike Main, this extends from the current class. Piece by piece this will replace
 * the Condor ContentAppearanceTracking class.
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 */
class ContentAppearanceTracking extends \ContentAppearanceTracking
{
    protected $newDb;
    protected $newAdminDb;

    public function setNewDb(\Doctrine\ORM\EntityManager $newDb)
    {
        $this->newDb = $newDb;        
    }

    public function getNewDb()
    {
        return $this->newDb;
    }

    public function setNewAdminDb(\Doctrine\ORM\EntityManager $newAdminDb)
    {
        $this->newAdminDb = $newAdminDb;        
    }

    public function getNewAdminDb()
    {
        return $this->newAdminDb;
    }

    public function getAppearances()
    {
        return $this->appearances;
    }

    /**
     * Commits the recorded content appearances of the page to the database
     * Called by Main object after rendering a page
     *
     * @param string $page
     */
    public function save($page)
    {
        if ($this->saveNotPermitted($page)) {
            throw new \ContentAppearanceSaveRejectedException();
        }

        $userId = $this->checkForUser();

        $qbContentAppearance = $this->newDb->createQueryBuilder();
        $qbContentAppearance->select('cat.id, cat.contentKey, cat.contentType, cat.configuration')
                            ->from('Toolbox\Entity\ContentAppearance', 'cat')
                            ->where('cat.page = :page')
                            ->setParameter('page', $page);
        
        $contentAppearanceResult = $qbContentAppearance->getQuery()->getResult();

        $contentAppearanceCheck = $contentAppearanceResult;

        $updateQuery = $this->newDb->createQueryBuilder();
        $updateQuery->update('Toolbox\Entity\ContentAppearance', 'cat')
                    ->set('cat.configuration', ':configuration')
                    ->set('cat.userId', ':userId')
                    ->set('cat.modified', ':modified')
                    ->where('cat.page = :page')
                    ->andWhere('cat.contentKey = :contentKey')
                    ->andWhere('cat.contentType = :contentType')
                    ->setParameter('page', $page)
                    ->setParameter('userId', $userId);

        foreach ($this->appearances as $appearance) {
            $configurationJson = json_encode($appearance['configuration']);

            $contentAppearanceRecord = $this->getExistingRecord($contentAppearanceCheck, $appearance);

            if ($contentAppearanceCheck != null && $contentAppearanceRecord) {
                if ($contentAppearanceResult[$contentAppearanceRecord]['configuration'] != $configurationJson) {
                    $updateQuery->setParameter('configuration', $configurationJson)
                                ->setParameter('contentType' , $appearance['contentType'])
                                ->setParameter('contentKey', $appearance['contentKey'])
                                ->setParameter('modified', date('Y-m-d H:i:s'));
                           
                    $updateQuery->getQuery()->execute();
                }
            } else {
                // Add record
                $contentAppearanceRecord = new \Toolbox\Entity\ContentAppearance();
                $created = new \DateTime();
                $modified = new \DateTime();
                $contentAppearanceRecord->setCreated($created);
                $contentAppearanceRecord->setModified($modified);
                $contentAppearanceRecord->setContentType($appearance['contentType']);
                $contentAppearanceRecord->setContentKey($appearance['contentKey']);                
                $contentAppearanceRecord->setPage($page);
                $contentAppearanceRecord->setConfiguration($configurationJson);
                $contentAppearanceRecord->setUserId($userId);
                $this->newDb->persist($contentAppearanceRecord);
                $this->newDb->flush();
            }
        }
        $this->appearances = array();
    }

    protected function getExistingRecord($contentAppearanceResult, $appearance)
    {
        $contentType = $appearance['contentType'];
        $contentKey = $appearance['contentKey'];
        foreach ($contentAppearanceResult as $keyResult => $valResult)
        {
            if ($valResult['contentType'] == $contentType && $valResult['contentKey'] == $contentKey) {
                unset($contentAppearanceResult[$keyResult]);
                return  $keyResult;
            }
        }

        return false;
    }

    protected function checkForUser()
    {
        return (\User::isLoggedIn()) ? \User::isLoggedIn() : 0;
    }
}
<?php

/**
 * Description of Content
 *
 * @author igor sorokin
 */

namespace PhoenixProperties\Service;

class GoogleMapContent extends \Blocks\Service\Blocks
{

    //============================================================================================================
    public function dynaMapData()
    {
        if (!isset($GLOBALS['tc_dynamic_data']['loewsMap'])) {
            $GLOBALS['tc_dynamic_data']['loewsMap'] = array();
        }
        $lmData = &$GLOBALS['tc_dynamic_data']['loewsMap'];
        $em = $this->getDefaultEntityManager();
        $lmData['hotels'] = $em->createQueryBuilder()
                        ->select(
                                array('pp.name',
                                    'pp.description',
                                    'pp.address',
                                    'pp.city',
                                    'pp.state',
                                    'pp.zip',
                                    'pp.country',
                                    'pp.latitude',
                                    'pp.longitude',
                                    'pp.group',
                                    'pp.labelX',
                                    'pp.labelY',
                                    'pp.phoneNumber',
                                    'pp.tollfreeNumber',
                                    'pp.code',
                                    'pp.url',
                                    'pp.id'
                        ))
                        ->from('PhoenixProperties\Entity\PhoenixProperty', 'pp')
                        ->where("pp.status=1 and pp.latitude <> '' and pp.longitude <> '' ")
                        ->getQuery()->getResult();

        $lmData['featuredPlaces'] = $em->createQueryBuilder()
                        ->select('mm')->from('MapMarkers\Entity\MapMarkers', 'mm')
                        ->where("mm.title!='blacklist' and mm.status = 1")
                        ->orderBy("mm.orderNumber", "ASC")
                        ->getQuery()->getArrayResult();

        $lmData['noShowPlaces'] = $em->createQueryBuilder()
                        ->select('mm.description')->from('MapMarkers\Entity\MapMarkers', 'mm')
                        ->where("mm.title='blacklist'  and mm.status = 1")
                        ->getQuery()->getResult();

        $lmData['noShowPlaces'] = count($lmData['noShowPlaces']) ? explode(',', $lmData['noShowPlaces'][0]['description']) : array();

        foreach ($lmData['hotels'] as $key =>$hotel) {
            $image = $em->createQueryBuilder()
                    ->select('f')
                    ->from('MediaManager\Entity\MediaManagerFileAttachments', 'a')
                    ->where('a.parentItemId = :itemId AND a.parentModule = :module')
                    ->setParameter('itemId', $hotel['id'])
                    ->setParameter('module', 'phoenixProperties')
                    ->leftJoin('MediaManager\Entity\MediaManagerFiles', 'f', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.file = f.id')
                    ->getQuery()
                    ->getArrayResult();
            
            if (count($image)>0)
            {
                $lmData['hotels'][$key]['photo'] = $image[0]["path"]."/".$image[0]["name"];
            }
            else
            {
                $lmData['hotels'][$key]['photo'] = array();
            }
        }
        
        return $lmData['hotels'];
    }

}

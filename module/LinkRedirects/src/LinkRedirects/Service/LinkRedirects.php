<?php

/**
 * The LinkRedirects Service
 *
 * @category    Toolbox
 * @package     LinkRedirects
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Sravani Gundapuneni<sgundapuneni@travelclick.com>
 * @filesource
 */

namespace LinkRedirects\Service;

use LinkRedirects\Model\LinkRedirect;
use Zend\Stdlib\ArrayObject;
use Zend\Form\Element;
use Zend\Form\Form;

class LinkRedirects extends \ListModule\Service\Lists
{

    protected $isScan = false;
    protected $entityName;
    protected $categories;
    protected $modelClass = "LinkRedirects\Model\LinkRedirect";

    public function __construct()
    {

        $this->entityName = "LinkRedirects\Entity\LinkRedirects";
    }

    public function getList_404()
    {
        $qbPage = $this->defaultEntityManager->createQueryBuilder();
        $qbPage->select('se')
                ->from('LinkRedirects\Entity\LinkRedirectsError', 'se')
                ->where('se.status = 1');

        $result = $qbPage->getQuery()->getResult();

        // var_dump($result);
        return $result;
    }

    public function getRedirectUrl($baseUrl)
    {
        $qbPage = $this->defaultEntityManager->createQueryBuilder();
        $qbPage->select('li')
                ->from('LinkRedirects\Entity\LinkRedirects', 'li')
                ->where('li.incomingUrl = :id')
                ->andWhere('li.status = 1')
                ->setParameter('id', $baseUrl);

        $result = $qbPage->getQuery()->getResult();

        // var_dump($result);
        return $result;
    }

    public function setLinkRedirects($insertValues)
    {
        return;
        $insertValuesArray = array(0 => $insertValues);
//        echo '<pre>';
//        print_r($insertValuesArray);
//        echo '</pre>';
        $linkRedirectArray = array();

        foreach ($insertValuesArray as $valResult) {
            $linkRedirectArray[] = array(
                'requestUri' => $valResult->getRequestUri()
            );
        }

        $siteroot = $this->getConfig()->get(array('templateVars', 'siteroot'));
        $siteUrlRecorded = $linkRedirectArray[0]['requestUri'];
        $siteUrlMissed = $siteroot . ltrim($siteUrlRecorded, "/");
        $fieldValue = new \LinkRedirects\Entity\LinkRedirectsError();
        $fieldValue->setUrl($siteUrlMissed);
        $fieldValue->setStatus(1);

        $this->getDefaultEntityManager()->persist($fieldValue);
        $this->getDefaultEntityManager()->flush();
    }

    public function import($file)
    {
        $filename = $file['tmp_name'];

        $fileCsv = fopen($filename, "r+");
        while (($csvData = fgetcsv($fileCsv, 10000, ",")) !== FALSE) {

            if ($csvData[0]) {
                $fieldValue = new \LinkRedirects\Entity\LinkRedirects();
                $fieldValue->setIncomingUrl($csvData[0]);
                $fieldValue->setRedirectUrl($csvData[1]);
                $fieldValue->setResponse($csvData[2]);
                $fieldValue->setStatus(1);
                $this->getDefaultEntityManager()->persist($fieldValue);
                $this->getDefaultEntityManager()->flush();
            }
        }
    }

}

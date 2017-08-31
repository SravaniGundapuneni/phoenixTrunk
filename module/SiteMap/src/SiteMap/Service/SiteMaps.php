<?php
/**
 * The SiteMap Service
 *
 * @category    Toolbox
 * @package     SiteMap
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.5
 * @since       File available since release 13.5
 * @author      Alex Kotsores <akotsores@travelclick.com>
 * @filesource
 */
namespace SiteMap\Service;

use SiteMap\Model\SiteMap;
use SiteMap\Entity\PhoenixSiteMap;

class SiteMaps extends \ListModule\Service\Lists
{
    protected $isScan = false;
    protected $orderList = true;
    protected $entityName;
    protected $categories;
    protected $modelClass = "SiteMap\Model\SiteMap";

    public function __construct()
    {
        $this->entityName = "SiteMap\Entity\PhoenixSiteMap";
    }
    
    private function getPageTitle($filename)
    {
        // Open the file  
        $fp = @fopen($filename, 'r');   

        // Add each line to an array 
        if ($fp) { 
           $lines = explode("\n", fread($fp, filesize($filename))); 
        }
        foreach($lines as $line){
            $startPos = strpos($line, 'pagename');
            
            if($startPos) {
                $startPos += 9;
                $titleStart = strpos($line, "'", $startPos)+1;
                $titleEnd = strrpos($line, "'");
                return substr($line, $titleStart, $titleEnd-$titleStart);
            }
            
        } 
        return false;
    }
    
    public function scanFolder()
    {
        $this->isScan = true;
        //clear records in table
        $em = $this->getDefaultEntityManager();
        
        $entries = $em->getRepository($this->entityName)->findAll();
        
        foreach($entries as $entry){
            $em->remove($entry);
        }
        $em->flush();
        
        //array of directories to ignore
        $ignoreList = array(
            "sockets",
            "templates",
            "config"
        );
        
        $di = new \RecursiveDirectoryIterator(SITE_PATH);
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            $file_parts = pathinfo($filename);
            
            $validFile = 1;
            
            if (($file_parts['extension'] == 'php' || (stripos($file_parts['extension'], 'htm') !== false)))
            {
                //check directory names on $ignoreList
                foreach($ignoreList as $ignore)
                {
                    if (stripos($file_parts['dirname'], $ignore) == true)
                    {
                        $validFile = 0;
                    }
                }
                
                if ($file_parts['filename'] == "404")
                {
                    $validFile = 0;
                }
                
                if ($validFile == 1)
                {
                    $title = $this->getPageTitle($filename);
                    
                    if ($title)
                    {
                        $data = array(
                            'dataSection' => str_replace(SITE_PATH, '', $file_parts['dirname']),
                            'page' => $file_parts['filename'],
                            'areaKey' => 'root',
                            'dynamicPage' => 0,
                            'title' => $title
                        );

                        $this->save($this->createModel(), $data);
                    }
                }
            }
        }
        
        $this->addDynamicPages();
        
        $this->generateXML();
    }
    
    public function addDynamicPages()
    {
        $pagesService = $this->getServiceManager()->get('phoenix-pages');
        $pages = $pagesService->getItems();
        
        foreach ($pages as $page)
        {
            $data = array(
                'dataSection' => $page->getDataSection(),
                'page' => $page->getPageKey(),
                'areaKey' => 'root',
                'dynamicPage' => 1,
                'title' => strip_tags($page->getBlocks())
            );
            $this->save($this->createModel(), $data);
        }
    }
    
    public function generateXML() {
        $items = $this->getItems();
        $itemsArray = array ();
        $xml = new \SimpleXMLElement('<root/>'); 
        
        foreach($items as $index =>$item){
            $itemXml = $xml->addChild('item');
            $itemXml->addChild('title', $item->getTitle());
            $itemXml->addChild('page', $item->getPage());
            $itemXml->addChild('dataSection', $item->getDataSection());
            $itemXml->addChild('created', $item->getCreated()->format('Y-m-d h:i:s'));
            $itemXml->addChild('modified', $item->getModified()->format('Y-m-d h:i:s'));
        }

        $fp = fopen(SITE_PATH . '/sitemap.xml', 'w');
        fwrite($fp, $xml->asXML());
        fclose($fp);
    }
    
    /**
     * draft
     * @param  array $items
     * @return void
     */
    public function draft($items)
    {
        $parent = parent::draft($items);
        $this->generateXML();
        return $parent;
    }

    /**
     * archive
     * @param  array $items
     * @return void
     */
    public function archive($items)
    {
        parent::archive($items);
        $this->generateXML();
    }

    /**
     * publish
     * @param  array $items
     * @return void
     */
    public function publish($items)
    {
        parent::publish($items);
        $this->generateXML();
    }

    /**
     * trash
     * @param  array $items
     * @return void
     */
    public function trash($items)
    {
        parent::trash($items);
        $this->generateXML();
    }
    
    /**
     * save
     * @param  array $items
     * @return void
     */
    public function save($model, $data)
    {
        parent::save($model, $data);
        if(!$this->isScan)
            $this->generateXML();
    }
    
    public function getPropertyIdOptions ()
    {
        //echo "I am in Pages Service's getHotelOption<br/>";
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $hotels = $this->getDefaultEntityManager()->getRepository('PhoenixProperties\Entity\PhoenixProperty')->findBy(array('status' => 1));

        foreach ($hotels as $keyHotel => $valHotel) {
            $options[$valHotel->getId()] = $valHotel->getName();
        }
        //var_dump($options);
        return $options;
    }
}
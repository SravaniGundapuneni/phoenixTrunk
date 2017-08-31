<?php

namespace Style\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

class SocketsController extends BaseSockets
{
    public function saveCssAction() 
    {
        $data = $this->params()->fromPost('css-data');
        
        $data = urldecode($data);
        
        $file = SITE_PATH . "/templates/main/css/main.css";
        unlink($file);
        if(!file_exists($file))
        {
            $a = fopen($file, 'x') or die('Failed to open file for writing');

            fwrite($a, $data);

            fclose($a);
            
            $results = new JsonModel(array("Success"=>"File Saved!"));
            return $results;
        }
        else
        {
            $results = new JsonModel(array("Failed"=>"File Already Exists!"));
            return $results;
        }
    }    
}
<?php

namespace LayoutEditor\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

class SocketsController extends BaseSockets
{
    public function saveLayoutAction() 
    {
        $status        = '500'; // error if save does not completely succeed
        $message       = 'Template saved successfully';
        $data = $this->params()->fromPost('content');
        $layoutName = $this->params()->fromPost('layoutName');
        $data = '<div class="container">'.$data.'</div>';

        $file = SITE_PATH . "/templates/main/layouts/{$layoutName}.phtml";
        unlink($file);
        
        try{
            if(!file_exists($file)){
                $a = fopen($file, 'x');
                if (!$a) {
                    throw new \Exception('Failed to open file for writing');  
                }
                fwrite($a, $data);
                fclose($a);
            }else{
                $message = "File Already Exists!";
            }
            
        } catch(\Exception $e) {
            $status  = 500;
            $message = $e->getMessage();
        }

        return new JsonModel(array(
            'message' => $message,
            'status'  => $status
        ));
    }
    
    public function deleteLayoutAction() 
    {
        $fileName = $this->params()->fromPost('layout-name');
        
        unlink(SITE_PATH . "/templates/main/layouts/{$fileName}");  
        
        if(is_dir($tmplDir)){
            $this->delete_dir($tmplDir);
        }
        else
        {
            $results = new JsonModel(array("Success"=>"Layout not found!"));
            return $results;
        }
        $results = new JsonModel(array("Success"=>"Layout has been deleted!"));
        return $results;
    }
}
<?php

namespace TemplateBuilder\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

/*
 * TODO: use HTTP response codes in socket responses
 */
class SocketsController extends BaseSockets
{
    const CORE_TEMPLATES       = '/templates/main/core-templates/';
    const EDIT_TEMPLATES       = '/templates/main/edit-templates/';
    const TEMPLATE_PERMISSIONS = 0777;

    public function getTemplateFileAction() 
    {
        $tmplId   = $this->params()->fromPost('tmplName');
        $fileName = $this->params()->fromPost('fileName');
        $file     = readfile(PHOENIX_PATH."/module/PhoenixTemplates/view/phoenix-templates/{$tmplId}/{$fileName}");
        echo $file;

        return new JsonModel(array(
            "Success" => "Template Loaded Successfully!",
            "result"  => $file
        ));
    }
    
    public function checkForExistingWidgetAction() 
    {
        $widgetName     = $this->params()->fromPost('widgetName');
        $uniqueWidgetId = $this->params()->fromPost('uniqueWidgetId');
        $fileName       = $this->params()->fromPost('sub-tmpl-name');
        $uniqueWidget   = $widgetName.$uniqueWidgetId;
        $tmplName       = str_replace(".phtml", "", $fileName);
        $widgetDir      = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$uniqueWidget}";

        if (file_exists ($widgetDir)) {
            $response = array(
                'Failed' => "Folder Already Exists!",
                'status' => false
            );
        } else {
            $response = array(
                'Success' => 'Folder does not Exists!',
                'status'  => true 
            );
        }
        
        return new JsonModel($response);
    }
    
    public function addWidgetAction() 
    {
        $fileName       = $this->params()->fromPost('sub-tmpl-name');
        $tmplName       = str_replace(".phtml", "", $fileName);
        $uniqueWidgetId = $this->params()->fromPost('uniqueWidgetId');
        $widgetName     = $this->params()->fromPost('widgetName');
        
        mkdir(SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/", self::TEMPLATE_PERMISSIONS);
        $widgetDir = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/";
        mkdir($widgetDir, self::TEMPLATE_PERMISSIONS);
        
        $uniqueWidgetDir = $widgetDir.$widgetName.$uniqueWidgetId."/";
        mkdir($uniqueWidgetDir, self::TEMPLATE_PERMISSIONS);
        $uniqueWidget = $widgetName.$uniqueWidgetId;
        
        mkdir(SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$uniqueWidget}/config/", self::TEMPLATE_PERMISSIONS);
        
        $widget_name_with_hyphens = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $widgetName));
        $widgetConfigSource       = PHOENIX_PATH . "/widget/{$widgetName}/config/";
        $widgetConfigDest         = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$uniqueWidget}/config/";
        $this->recurseCopy($widgetConfigSource, $widgetConfigDest, $uniqueWidget);
        
        $source = PHOENIX_PATH . "/widget/{$widgetName}/view/".$widget_name_with_hyphens."/helper/";
        $dest   = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$uniqueWidget}";
        $status = $this->recurseCopy($source, $dest, $uniqueWidget);

        return new JsonModel(array(
            "Success" => "Widget has been added to the template!",
            "res"     => $widgetName."<br>".$uniqueWidget."<br>".$uniqueWidgetDir."<br>".$source."<br>".$dest
        ));
    }
    
    public function deleteWidgetAction() 
    {
        $fileName       = $this->params()->fromPost('sub-tmpl-name');
        $uniqueWidgetId = $this->params()->fromPost('uniqueWidgetId');
        $widgetName     = $this->params()->fromPost('widgetName');
        $tmplName       = str_replace(".phtml", "", $fileName);
        $uniqueWidget   = $widgetName.$uniqueWidgetId;
        $widgetDir      = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$uniqueWidget}/";
        
        if (is_dir($widgetDir)){
            $this->delete_dir($widgetDir);
            $response = array(
                'Success' => 'Widget has been delete!'
            );
        } else {
            $response = array(
                'Success' => 'Widget not found!'
            );
        }

        return new JsonModel($response);
    }

    
    public function saveTemplateAction() 
    {
        $status        = 200; // if action completely succeeds
        $message       = 'Template saved successfully';
        $fileName      = $this->params()->fromPost('sub-tmpl-name');
        $addGlobalFlag = $this->params()->fromPost('add-global-flag');
        $data          = $this->params()->fromPost('sub-tmpl-data');
        $sideBarPos    = $this->params()->fromPost('sidebar-position');
        $tmplName      = str_replace(".phtml", "", $fileName);
        $data          = urldecode($data);
        
        mkdir(SITE_PATH . self::CORE_TEMPLATES . $tmplName, self::TEMPLATE_PERMISSIONS);
        
        $tmplFile = SITE_PATH . "/{$tmplName}.php";
        if(file_exists($tmplFile))
            unlink($tmplFile);
        $config = '<?php return array(
                        "page" => "'.$tmplName.'",
                        "template" => "'.$fileName.'",
                        "templateVars" => array(
                                "pagename" => "Corporate '.ucfirst($tmplName).'",
                            ), 
                       ); ?>';
        try {
            
            if (!file_exists($tmplFile)) {
                $a = fopen($tmplFile, 'x');

                if (!$a) {
                    throw new \Exception('Failed to open file for writing');  
                }
                fwrite($a, $config);
                fclose($a);
            }
            
            $file1 = SITE_PATH . self::EDIT_TEMPLATES . "{$fileName}";
            if(file_exists($file1))
                unlink($file1);

            if (!file_exists($file1)) {
                $a = fopen($file1, 'x');

                if (!$a) {
                    throw new \Exception('Failed to open file for writing');
                }
                fwrite($a, $data);
                fclose($a);
            }
            
            $includeHeader  = '<?php require_once SITE_PATH . "/templates/main/inc.header.php"; ?>';
            $includeFooter  = '<?php require_once SITE_PATH . "/templates/main/inc.footer.php"; ?>';
            $includeSidebar = '<?php require_once SITE_PATH . "/templates/main/sidebar.phtml"; ?>';
            
            //  adding sidebar html
            $sidebarData = array(
                "sidebar-left"  => '<div class="row"><div class="column large-3 medium-3 small-3 sidebar-left text-left">'.$includeSidebar.'</div><div class="column large-9 medium-9 small-9">'.$data.'</div></div>',
                "sidebar-right" => '<div class="row"><div class="column large-9 medium-9 small-9">'.$data.'</div><div class="column large-3 medium-3 small-3 sidebar-right text-right">'.$includeSidebar.'</div></div>'
            );
            
            if(isset($sideBarPos) AND !($sideBarPos == "sidebar-none"))
                $data = $sidebarData[$sideBarPos];
            
            $file = SITE_PATH . "/templates/main/{$fileName}";
            if(file_exists($file))
                unlink($file);
            
            $excludeFiles = array("header","footer","sidebar");
                if (!(in_array($tmplName, $excludeFiles))) {
                    $data = $includeHeader . $data;
                    $data = $data . $includeFooter;
                    error_log($data,3,'/www/riz1.php');
                }
            
            if (!file_exists($file)) {
                
                if (!file_put_contents($file, $data)) {
                    throw new \Exception('Failed to open file for writing');
                }
                
                $htmlString = file_get_contents($file);
                
                $htmlStringWithWidgetFunctions = $this->replaceShortTags($htmlString);

                file_put_contents($file, $htmlStringWithWidgetFunctions);

                $this->minifyCssAndJs($tmplName);
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
    
    public function deleteTemplateAction() 
    {
        $fileName = $this->params()->fromPost('sub-tmpl-name');
        $tmplName = str_replace(".phtml", "", $fileName);
        $tmplDir  = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/";
        
        unlink(SITE_PATH . self::EDIT_TEMPLATES . "{$fileName}");  
        unlink(SITE_PATH . "/templates/main/{$fileName}");
        unlink(SITE_PATH . "/{$tmplName}.php");
        
        //  delete minified css and js when template gets deleted
        unlink(SITE_PATH . "/templates/main/css/{$fileName}.min.css");  
        unlink(SITE_PATH . "/templates/main/js/{$fileName}.min.js");  
        
        if (is_dir($tmplDir)){
            $this->delete_dir($tmplDir);
            $response = array(
                "Success" => "Template has been deleted!"
            );
        } else {
            $response = array(
                "Success" => "Template not found!"
            );
        }

        return new JsonModel($response);
    }
    
    public function saveWidgetFilesAction() 
    {
        $tmplName             = $this->params()->fromPost('sub-tmpl-name');
        $widgetId             = $this->params()->fromPost('widget-id');
        $currentTemplate      = $this->params()->fromPost('current-template');
        $widgetNameWithDashes = $this->params()->fromPost('widget-name-with-dashes');
        $data["phtml"]        = $this->params()->fromPost('html-data');
        $data["css"]          = $this->params()->fromPost('css-data');
        $data["js"]           = $this->params()->fromPost('js-data');
        $data["php-config"]   = $this->params()->fromPost('php-config-data');
        $widgetName           = strtolower($this->params()->fromPost('widget-name'));
        $widget               = $widgetName . $widgetId;
        $status               = 200;
        $message              = "Widget updated!";
        
        try {
            foreach ($data as $key => $value) {

                $fileName = $widgetNameWithDashes."-".$currentTemplate.".".$key;

                if ($key !== "phtml") {
                    if ($key == "php-config") {
                        $file = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$widget}/config/widget.config.php";
                    } else {
                        $file = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$widget}/{$key}/{$fileName}";
                    }
                } else {
                    $file = SITE_PATH . self::CORE_TEMPLATES . "{$tmplName}/widgets/{$widget}/{$fileName}";
                }
                    
                unlink($file);
                if (!file_exists($file)) {

                    $a = fopen($file, 'x');

                    if (!$a) {
                        throw new \Exception('Failed to open file for writing');
                    }

                    fwrite($a, ($key == "php-config") ? htmlspecialchars_decode(urldecode($value)) : urldecode($value));
                    fclose($a);
                } else {
                    throw new \Exception('File already exists');
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $status  = 500;
        }

        return new JsonModel(array(
            'status'  => $status,
            'message' => $message
        ));
    }
    
    public function readWidgetConfigFileAction() 
    {
        $tmplName   = $this->params()->fromPost('sub-tmpl-name');
        $widgetId   = $this->params()->fromPost('widget-id');
        $widgetName = strtolower($this->params()->fromPost('widget-name'));
        $widget     = $widgetName . $widgetId;
        $file       = SITE_PATH . self::CORE_TEMPLATES .$tmplName."/widgets/".$widget."/config/widget.config.php";

        if (file_exists($file)) {
            $data     = htmlspecialchars(file_get_contents($file));
            $response = array(
                "Success" => "Reading file now!",
                "data"    => $data
            );
        } else {
            $response = array(
                "Failed" => "widget.config.php does not exist!",
                $file
            );
        }

        return new JsonModel($response);
    }

    private function minifyCssAndJs($pageKey)
    {
        $assetService = $this->getServiceLocator()->get('assets');   
        $assetService->minifyCssAndJs($pageKey); 
    }

    private function recurseCopy($src,$dst, $widget_folder_name) 
    { 
        $dir = opendir($src); 
        mkdir($dst,self::TEMPLATE_PERMISSIONS); 

        while(false !== ( $file = readdir($dir)) ) { 

            if (( $file != '.' ) && ( $file != '..' )) { 

                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurseCopy($src . '/' . $file,$dst . '/' . $file, $widget_folder_name); 
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir);            
    }

    private function delete_dir($src) 
    { 
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->delete_dir($src . '/' . $file); 
                } 
                else { 
                    unlink($src . '/' . $file); 
                } 
            } 
        } 
        rmdir($src);
        closedir($dir); 
    }
    
    private function replaceShortTags($htmlString)
    {
        $htmlStringWithWidgetFunctions = $htmlString;
        if (preg_match_all('/\!\+(.*?)\+\!/', $htmlString, $matches)) {   
            $count = count($matches[0]);
            if($count > 0)
            {
                for ($i = 0; $i < $count; $i++) 
                {
                    $search = $matches[0][$i];
                    $param  = explode(',', $matches[1][$i]);
                    $replace = '<?php $options'.$i.' = array(
                        "widgetName" => "'.lcfirst($param[0]).'",
                        "widgetId" => "'.$param[1].'",
                        "templatePage" => "'.$param[2].'", 
                       );';
                    $replace .= 'echo $this->{$options'.$i.'["widgetName"]}($options'.$i.');?>';
                    $htmlStringWithWidgetFunctions = str_replace($search, $replace, $htmlStringWithWidgetFunctions);
                }
            }
        }
                
        return $htmlStringWithWidgetFunctions;
    }
}
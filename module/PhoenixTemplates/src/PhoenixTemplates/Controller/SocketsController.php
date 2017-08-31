<?php

namespace PhoenixTemplates\Controller;

use Zend\View\Model\JsonModel;
use ListModule\Controller\SocketsController as BaseSockets;

class SocketsController extends BaseSockets
{
    public function createHeaderAction() 
    {
        $data = $this->params()->fromPost('data');
        $data = urldecode($data);

        $file = SITE_PATH . "/templates/main/core-templates/header/header.phtml";
        unlink($file);
        if(!file_exists($file))
        {
            $a = fopen($file, 'x') or die('Failed to open file for writing');

            fwrite($a, $data);

            fclose($a);
            return json_encode(array(array("Success"=>"File Saved!")));
        }
        else
        {
            return json_encode(array(array("Failed"=>"File Already Exists!")));
        }
    }
    
    public function recurse_copy($src,$dst, $widget_folder_name) 
    { 
            $dir = opendir($src); 
            @mkdir($dst); 
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) { 
                    if ( is_dir($src . '/' . $file) ) { 
                        $this->recurse_copy($src . '/' . $file,$dst . '/' . $file, $widget_folder_name); 
                    } 
                    else 
                    {
        //                $file_parts = pathinfo($src . '/' . $file);
        //                if($file_parts['extension'] == "css")
        //                {
        //                    $data = file_get_contents($src . '/' . $file);
        //                    
        //                    $css .= '.'.$widget_folder_name.' { ';
        //                    $css .= $data;
        //                    $css .= ' }';
        //                    
        //                    $cssFile = $dst . '/' . $file;
        //                    $a = fopen($cssFile, 'w');
        //                    fwrite($a, $css);
        //                    fclose($a);
        //                }
        //                else
                            copy($src . '/' . $file, $dst . '/' . $file); 
                    } 
                } 
            } 
            closedir($dir); 
    }
    
    public function addHeaderFooterWidgetAction() 
    {
        $widgetType = $this->params()->fromPost("widgetType");

        $widgetId = $this->params()->fromPost("widgetId");
        $widget_name = $this->params()->fromPost("widgetName");
        $widget_name_with_hyphens = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $widget_name));

        $site_widget_dir = SITE_PATH . "/templates/main/core-templates/widgets/";

        $widget_folder_name = $widget_name_with_hyphens."_".$widgetId;

        $source = PHOENIX_PATH . "/widget/".$widget_name."/view/".$widget_name_with_hyphens."/helper/";
        $dest   = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widget_folder_name."/";


        if(!file_exists ($widget_folder_name)){
            mkdir($site_widget_dir.$widget_folder_name, 0777);
            $this->recurse_copy($source, $dest, $widget_folder_name);
        }
        else
        {
            $this->recurse_copy($source, $dest, $widget_folder_name);
        }
        return json_encode(array("Success"=>"Widget has been added to the template!"));
    }
    
    public function checkExistingTemplateAction() 
    {
        $dirname = $this->params()->fromPost("dirname");
        $filename = SITE_PATH . "/templates/main/core-templates/" . $dirname . "/";

        if (!file_exists($filename)) {
            mkdir($filename, 0777);
            mkdir($filename."/widgets/", 0777);
            return json_encode(array("Success"=>"The directory $dirname was successfully created."));
            exit;
        } else {
            return json_encode(array("Error"=>"The directory $dirname exists."));;
        }
    }
    
    public function createFooterAction() 
    {
        $data = $this->params()->fromPost("data");
        $data = urldecode($data);

        $file = SITE_PATH . "/templates/main/core-templates/footer/footer.phtml";
        unlink($file);
        if(!file_exists($file))
        {
            $a = fopen($file, 'x') or die('Failed to open file for writing');

            fwrite($a, $data);

            fclose($a);
            return json_encode(array(array("Success"=>"File Saved!")));
        }
        else
        {
            return json_encode(array(array("Failed"=>"File Already Exists!")));
        }
    }
    
    public function createSiteWidgetsAction() 
    {
        $tmpl_name = $this->params()->fromPost("tmplName");
        $widget_name = $this->params()->fromPost("widgetName");

        $site_widget_dir = SITE_PATH . "/templates/main/site-widgets/";

        $widget_folder_name = $tmpl_name."_".$widget_name;

        $source = SITE_PATH . "/templates/main/widgets/".$widget_name."/";
        $dest   = SITE_PATH . "/templates/main/site-widgets/".$widget_folder_name."/";


        if(!file_exists ($widget_folder_name)){
            mkdir($site_widget_dir.$widget_folder_name, 0777);
            $this->recurse_copy($source, $dest);
        }
        else
        {
            $this->recurse_copy($source, $dest);
        }
        return json_encode(array("Success"=>"Widget has been added to the template!"));
    }
    
    public function createSiteWidgetsConfigAction() 
    {
        
        $widgetId = $this->params()->fromPost("widgetId");
        $widgetType = $this->params()->fromPost("widgetType");
        $data = $this->params()->fromPost("data");
        $data = json_decode($data);

        $str = '';
        $str .= '<div class="'.$widgetId."-".$widgetType."-config".'">';
        foreach ($data->config->inputs as $input) {
            switch ($input->type) {
                case "select":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<label for="'.$widgetId."-".$widgetType.'-select">'.$input->label.'</label>';
                    $str .= '<select id="'.$widgetId."-".$widgetType.'-select" name="'.$widgetId."-".$widgetType.'-select" >';
                    foreach ($input->value as $key => $val) {
                        $str .= '<option value="'.$val.'">'.$key.'</option>';
                    }
                    $str .= '</select>';
                    $str .= '</div></div>';
                    break;
                case "checkbox":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<input id="'.$widgetId."-".$widgetType.'-checkbox" name="'.$widgetId."-".$widgetType.'-checkbox" type="checkbox">';
                    $str .= '<label for="'.$widgetId."-".$widgetType.'-checkbox ">'.$input->label.'</label>';
                    $str .= '</div></div>';
                    break;

                case "radio":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<input id="'.$widgetId."-".$widgetType.'-radio" name="'.$widgetId."-".$widgetType.'-radio" value="'.$input->value.'" type="radio">';
                    $str .= '<label for="'.$widgetId."-".$widgetType.'-radio ">'.$input->label.'</label>';
                    $str .= '</div></div>';
                    break;

                case "text":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<label for="'.$widgetId."-".$widgetType.'-text ">'.$input->label.'</label>';
                    $str .= '<input id="'.$widgetId."-".$widgetType.'-text" name="'.$widgetId."-".$widgetType.'-text" placeholder="'.$input->value.'"  value="'.$input->value.'" type="text">';
                    $str .= '</div></div>';
                    break;

                case "color":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<label for="'.$widgetId."-".$widgetType.'-color ">'.$input->label.'</label>';
                    $str .= '<input name="'.$widgetId."-".$widgetType.'-color" type="color" >';
                    $str .= '</div></div>';
                    break;

                case "a":
                    $str .= '<div class="row"><div class="small-1 columns">';
                    $str .= '<a href="'.$input->href.'" target="'.$input->target.'" id="'.$widgetId."-".$widgetType.'-a" name="'.$widgetId."-".$widgetType.'-a" >'.$input->label.'</a>';
                    $str .= '</div></div>';
                    break;

                default:
                    break;
            }
        }

        $str .= '</div>';
        return $str;
    }
    
    public function createTemplateAction() 
    {
        $tmplName = $this->params()->fromPost("tmplName");
        $data = $this->params()->fromPost("htmlData");

        $core_templates_dir = SITE_PATH . "/templates/main/core-templates/";

        mkdir($core_templates_dir.$tmplName, 0777);

        $file = SITE_PATH . "/templates/main/core-templates/".$tmplName."/".$tmplName.".phtml";
        unlink($file);
        if(!file_exists($file))
        {
            $a = fopen($file, 'x') or die('Failed to open file for writing');

            fwrite($a, $data);

            fclose($a);
            return json_encode(array(array("Success"=>"File Saved!")));
        }
        else
        {
            return json_encode(array(array("Error"=>"File Already Exists!")));
        }
    }
    
    public function delete_dir($src) 
    { 
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    delete_dir($src . '/' . $file); 
                } 
                else { 
                    unlink($src . '/' . $file); 
                } 
            } 
        } 
        rmdir($src);
        closedir($dir); 
    }
    
    public function deleteHeaderFooterAction() 
    {
        $widgetType = $this->params()->fromPost("widgetType");

        $widgetId = $this->params()->fromPost("widgetId");

        $src   = array(
            SITE_PATH . "/templates/main/core-templates/header/",
            SITE_PATH . "/templates/main/core-templates/footer/"
        );
        for($i=0; $i < count($src); $i++)
        {
            if(is_dir($src[$i]))
            {
                $this->delete_dir($src[$i]);
            }
            else
            {
                return json_encode(array("Success"=>"Widget not found!"));
            }
        }

        mkdir(SITE_PATH . "/templates/main/core-templates/header", 0777);
        mkdir(SITE_PATH . "/templates/main/core-templates/header/widgets", 0777);
        mkdir(SITE_PATH . "/templates/main/core-templates/footer", 0777);
        mkdir(SITE_PATH . "/templates/main/core-templates/footer/widgets", 0777);

        return json_encode(array("Success"=>"Widget has been delete!"));

    }
    
    public function deleteHeaderFooterFilesAction() 
    {
        $src   = array(
            SITE_PATH . "/templates/main/core-templates/header/header.phtml",
            SITE_PATH . "/templates/main/core-templates/footer/footer.phtml"
        );
        for($i=0; $i < count($src); $i++)
        {
            unlink($src[$i]);
        }
        return json_encode(array("Success"=>"Header and Footer has been deleted!"));
    }
    
    public function deleteHeaderFooterWidgetAction() 
    {
        $widgetType = $this->params()->fromPost("widgetType");

        $widgetId = $this->params()->fromPost("widgetId");

        $src   = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widgetId."/";

        if(is_dir($src)){
            $this->delete_dir($src);
        }
        else
        {
            return json_encode(array("Success"=>"Widget not found!"));
        }
        return json_encode(array("Success"=>"Widget has been delete!"));
    }
    
    public function saveHeaderFooterWidgetsAction() 
    {
        
        $data = $this->params()->fromPost("data");
        $data = urldecode($data);
        $filetype = $this->params()->fromPost("filetype");
        $widget_name = $this->params()->fromPost("widgetName");

        $widgetType = $this->params()->fromPost("widgetType");

        $widgetId = $this->params()->fromPost("widgetId");

        $src   = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widgetId."/";

        //$site_widget_dir = SITE_PATH . "/templates/main/site-widgets/";

        $widget_folder_name = $tmpl_name."_".$widget_name;
        switch ($filetype) {
            case "phtml":
                $file = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widgetId."/widget.phtml";
                break;
            case "css":
                $file = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widgetId."/css/widget.css";
                break;
            case "js":
                $widgetName = explode("_", $widget_name);
                $data = stripslashes($data);
                $file = SITE_PATH . "/templates/main/core-templates/".$widgetType."/widgets/".$widgetId."/js/widget.js";
                break;
            default:
                break;
        }

        //if($filetype == "css")
        //{
        //    
        //    $css .= '#'.$widgetId.' {';
        //    $css .= $data;
        //    $css .= '}';
        //    $data = $css;
        //}

        $a = fopen($file, 'w');
        fwrite($a, $data);
        fclose($a);

        return json_encode(array(array("Success"=>"File Saved!"),$data));

    }
    
    public function saveSiteWidgetsAction() 
    {
        $data = $this->params()->fromPost("data");
        $data = urldecode($data);
        $filetype = $this->params()->fromPost("filetype");
        $widget_name = $this->params()->fromPost("widgetName");

        $site_widget_dir = SITE_PATH . "/templates/main/site-widgets/";

        $widget_folder_name = $tmpl_name."_".$widget_name;
        switch ($filetype) {
            case "phtml":
                $file = SITE_PATH . "/templates/main/site-widgets/".$widget_name."/widget.phtml";
                break;
            case "css":
                $file = SITE_PATH . "/templates/main/site-widgets/".$widget_name."/css/widget.css";
                break;
            case "js":
                $widgetName = explode("_", $widget_name);
                $data = stripslashes($data);
                $file = SITE_PATH . "/templates/main/site-widgets/".$widget_name."/js/".$widgetName[1].".js";
                break;
            default:
                break;
        }


        $a = fopen($file, 'w');
        fwrite($a, $data);
        fclose($a);

        return json_encode(array(array("Success"=>"File Saved!"),$data));
    }
    
}
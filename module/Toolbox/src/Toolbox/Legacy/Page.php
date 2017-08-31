<?php
/**
 * Legacy Page Adapter
 *
 * This is the legacy adapter that corresponds to the condor Page class.
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
 * Legacy Page Adapter
 *
 * This is the legacy adapter that corresponds to the condor Page class.
 * 
 * Unlike Main, this extends from the current class. Piece by piece this will replace
 * the Condor page class.
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
class Page extends \Page
{
    protected $newDb;
    protected $newAdminDb;

    public function setNewDb($newDb)
    {
        $this->newDb = $newDb;        
    }

    public function getNewDb()
    {
        return $this->newDb;
    }

    public function setNewAdminDb($newAdminDb)
    {
        $this->newAdminDb = $newAdminDb;        
    }

    public function getNewAdminDb()
    {
        return $this->newAdminDb;
    }

    //THIS DOES NOT WORK, SO DON'T EXPECT IT TO BE WORKING.
    // public function renderContent($main)
    // {
    //     if ($main->isRestrictedPage()) {
    //         $main->requireUserAuthorization();
    //     }
    //     /**
    //      * Serve a socket, otherwise a page
    //      */
    //     if($main->socketRequested())
    //     {
    //         /**
    //          * Lets add the content filters, if socket must be applied manually
    //          *
    //          * This is used at least by the contentBlocks socket fetchBlock to
    //          * convert the tags into editable dojo widgets
    //          */
    //         $this->_registerMainFilters();

    //         /**
    //          * Check if this is a restricted page
    //          */
    //         if($this->_isRestrictedPage())
    //         {
    //             //die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);
    //             if (!$this->_requireUserAuthorization()) {
    //                 return false;
    //             }
    //          }
    //          $this->legacyAdapter->callEvent('onBeforeRenderContent');
    //         echo $this->currentModuleObj->callSocket($_REQUEST['socketName']);
    //         $this->close();
    //     }
    //     else
    //     {
    //         /**
    //          * If the user can write we add the editing tools,
    //          * Otherwise we just add the base scripts
    //          */
    //         if(Auth::canWrite())
    //         {
    //             $this->_addPageEditingTools();
    //         }
    //         else
    //         {
    //             $this->_addCondorBaseScripts();
    //         }

    //         /**
    //          * Lets add the content filters, if socket must be applied manually
    //          *
    //          * This is used at least by the contentBlocks socket fetchBlock to
    //          * convert the tags into editable dojo widgets
    //          */
    //         $this->_registerMainFilters();
    //         $this->beginOutputBuffer();

    //         /**
    //          * Check if this is a restricted page
    //          */
    //         if($this->_isRestrictedPage())
    //         {
    //             if (!$this->_requireUserAuthorization()){
    //                 return false;
    //             }
    //         }

    //         $this->legacyAdapter->callEvent('onBeforeRenderContent');

    //         if(!$this->taskRequested())
    //         {
    //             if( defined('CONDOR_RENDER_STATIC_CONTENT_ONLY')
    //                 && isset($this->localCXML->content['publishMethod'])
    //                 && $this->localCXML->content['publishMethod'] == 'dynamic')
    //             {
    //                 $this->page->setVar('mainContent', SitePublisher::PLACE_HOLDER_MAIN_CONTENT);
    //             }
    //             else
    //             {
    //                 switch($this->localCXML->content['type'])
    //                 {
    //                     case 'module' :
    //                         if($this->localCXML->content->conf->defaultTask)
    //                         {
    //                             $this->defaultTask = trim((string) $this->localCXML->content->conf->defaultTask);
    //                         }
    //                         else if ($this->currentModuleObj->moduleCXML->defaultTask)
    //                         {
    //                             $this->defaultTask = trim((string) $module->moduleCXML->defaultTask);
    //                         }
    //                         if($this->currentTask === false)
    //                         {
    //                             $this->currentTask = $this->defaultTask;
    //                         }

    //                         if($this->currentTask == $this->defaultTask)
    //                         {
    //                             $this->isDefaultTask = true;
    //                         }

    //                         break;

    //                     case 'template' :
    //                         $this->page->setVar('mainContent', $this->page->outputByTemplate((string) $this->localCXML->content->template));
    //                         break;

    //                     default  :
    //                         //Pass the needed vars to the page class and configure it
    //                         $this->page->setVar('mainContent', $this->page->capture((string) $this->localCXML->content));
    //                 }
    //             }
    //         }

    //         if($this->currentModuleObj && !empty($this->currentTask))
    //         {
    //             /**
    //              * Add taskPreview if need be
    //              */
    //             if (!$this->currentModuleObj->hasTask('taskPreview'))
    //             {
    //                 $taskPreview = implode(array(
    //                     '<task>',
    //                         '<name>taskPreview</name>',
    //                         '<callScript>overview</callScript>',
    //                         '<authLevel>authRead</authLevel>',
    //                         '<taskType>write</taskType>',
    //                         '<directLink>false</directLink>',
    //                         '<showTaskList>true</showTaskList>    ',
    //                     '</task>'
    //                 ));

    //                 $this->currentModuleObj->addTask(
    //                     simplexml_load_string(
    //                         $taskPreview    
    //                     )
    //                 );
    //             }
                
    //             $this->currentModuleObj->loadTaskCXML($this->currentTask);

    //             if($this->currentModuleObj->taskCXML->showTaskList == 'false')
    //             {
    //                 $this->page->setVar('showTaskList', false);
    //             }
    //             else
    //             {
    //                 $this->page->setVar('showTaskList', true);
    //             }


    //             createCurrentTaskMenu();

    //             if(file_exists(slashomatic($this->paths['templateBaseDir']) . 'moduleStyle.css'))
    //             {
    //                 $this->page->addStylesheet($this->page->getVar('templateBaseHREF') . '/moduleStyle.css');
    //             }

    //             //Add a place holder if we are rendering this page for the site publisher
    //             if(defined('CONDOR_RENDER_STATIC_CONTENT_ONLY')
    //                 && isset($this->currentModuleObj->taskCXML->publishMethod)
    //                 && $this->currentModuleObj->taskCXML->publishMethod == 'dynamic')
    //             {
    //                 $this->page->setVar('mainContent', SitePublisher::PLACE_HOLDER_MAIN_CONTENT);
    //             }
    //             //Otherwise render the actual content for the page
    //             else
    //             {
    //                 $this->page->setVar('mainContent', $this->currentModuleObj->callTask($this->currentTask));
    //             }
    //         }

    //         /**
    //          * Render the header toolbar,
    //          * Don't show it if page is popup
    //          */
    //         if (!isset($_REQUEST['pop']))
    //         {
    //             if($this->canEditPage())
    //             {
    //                 $this->_addInPageEditBar();
    //             }
    //             else if (Auth::canWrite())
    //             {
    //                 $this->_addBasicToolBar();
    //             }
    //         }
            
            
    //         $this->_displayErrorsOnWarning();
    //         $this->_callPageTemplate();
    //         $this->page->renderFinalPage($this->getOutputFromBuffer());

    //         return $this->page->renderContent($this);
    //     }        
    // }
     
    function getToolHTMLArray($tool, $templatePath)
    {
        $boxClass = (isset($tool['float']) && (string)$tool['float'] == 'right') ? ' right' : '';
        if(strtolower($tool['key']) == 'help')
        {
            $href = 'http://training.travelclick.com/Client_Training/Toolbox/Training_Information/Toolbox_Training.pdf';
        }
        else
        {
            $href = isset($tool['link']) ? $tool['link'] : "!+ LINK #virtualBaseArea/tools/{$tool['key']} +!";
        }
        $target = (isset($tool['newWindow']) && $tool['newWindow']) ? ' target="_blank"' : '';
        
        $imageModule = $templatePath.'/images/modules/box_'.$tool['key'].'.png';
        if(!file_exists(condorHREFToFile($imageModule))) $imageModule = $templatePath.'/images/modules/box_pukkaModules.png';

        return <<<EOD
        <div class="areaBox$boxClass">
            <a href="$href"$target>
                <span class="areaImage" style="background-image:url('$imageModule');"></span>
                <span class="areaTitle">!+ TEXT template_toolbox frontPageBox_{$tool['key']} dbAdmin htmlStrip +!</span>
            </a>
        </div>
EOD;

    }    
}
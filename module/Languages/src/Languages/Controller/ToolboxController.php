<?php
/**
 * Languages Toolbox Controller
 *
 * The ToolboxController for the Languages Modlue
 *
 * If it is a toolbox action for the Languages module.
 *
 * @category         Toolbox
 * @package          Languages
 * @subpackage       Controllers
 * @copyright        Copyright (c) 2013 TravelClick, Inc (http://travelclick.com)
 * @license          All Rights Reserved
 * @version          Release 13.5.5
 * @since            File available since release 13.5.5
 * @author           Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */

namespace Languages\Controller;

use Zend\Stdlib\ArrayObject;

use Zend\View\Model\ViewModel;

class ToolboxController extends \ListModule\Controller\ToolboxController
{
    /**
     * The base admin level for actions. Each action (or task within an action)
     * can require a higher adminLevel, but this is the lowest allowed.
     * 
     * @var string
     */    
    protected $baseAdminLevel = \Users\Service\Acl::PERMISSIONS_RESOURCE_SUPER_ADMIN;

    protected $actionDefaultAdminLevel = array('index' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN,
        'editList' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN,
            'import' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN, 
            'export' => \Users\Service\Acl::PERMISSIONS_RESOURCE_ADMIN);

    protected $tasksMenu = array(
        //'addItem'=>'Add Languages',
        'index' => 'View Languages',        
        'propertyLanguages'=>'Manage Hotel Languages', 
        'export' => 'Export Translations', 
        'import' => 'Import Translations');

    public function __construct()
    {
        $this->modsing = "Languages";
        parent::__construct();
    }

    public function exportAction()
    {
        set_time_limit(900); //15 minutes
        //Increase memory for this apparently often-weighty task
        ini_set('memory_limit', '650M');

        $languages = $this->getServiceLocator()->get('phoenix-languages');

        $exportArray = $languages->exportTranslations();

        $exportType = 'Xls';

        $exportObjectName = "\Languages\Mapper\{$exportType}";

        $viewModel = new ViewModel;

        $viewModel->setTerminal(true);

        $exportObject = $this->getServiceLocator()->get('phoenix-languages-mapper-xls');

        $exportObject->setData($exportArray);

        $exportObject->build();

        $exportObject->prepForSave();

        $exportName = 'translations_export_' . date('Y-m-d');

        $response = $this->getResponse();

        $headers = $response->getHeaders();

        $headers->addHeaderLine('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $headers->addHeaderLine('Content-Disposition', 'attachment;filename="'. $exportName .'.xlsx"');
        $headers->addHeaderLine('Cache-Control', 'max-age=0');

        //This extra stuff was added because just passing the exportObject->save call as the response content doesn't work on the servers.
        ob_start();
        $exportObject->save();
        $content = ob_get_contents();
        ob_end_clean();
        $response->setContent($content);

        return $response;
    }

    public function importAction()
    {
        set_time_limit(1800); //30 minutes
        //Increase memory for this apparently often-weighty task
        ini_set('memory_limit', '512M');

        $request = $this->getRequest();

        $form = new \MediaManager\Form\MediaManagerUploadForm();

        $viewModel = new ViewModel();

        $viewModel->setTemplate('phoenix-languages/toolbox/import');

        $viewModel->form = $form;


        $viewModel->formFinished = false;

        if ($request->isPost()) {
            $importObject = $this->getServiceLocator()->get('phoenix-languages-mapper-xls');

            $translationFile = $request->getFiles()->get('upload-file');

            $importObject->loadFromFile($translationFile['tmp_name']);

            $importObject->unwrap();

            $importArray = $importObject->getUnwrappedArray();

            $languages = $this->getServiceLocator()->get('phoenix-languages');

            $languages->importTranslations($importArray);

            $viewModel->formFinished = true;
        } 

        $form->get('upload-file')->setLabel('Import Translations File (xlsx)');


        return $viewModel;

        // $importText = new ArrayObject();

        // $importText['Test Module'] = new ArrayObject();

        // $importText['Test Module'][1] = new ArrayObject();

        // $importText['Test Module'][1]['fields'] = new ArrayObject();

        // $importText['Test Module'][1]['fields']['testField'] = new ArrayObject();

        // $importText['Test Module'][1]['fields']['testField']['defaultLanguageValue'] = 'Blue 2';

        // $importText['Test Module'][1]['fields']['testField']['translations'] = new ArrayObject();

        // $importText['Test Module'][1]['fields']['testField']['translations']['fr'] = 'Imported French Translation';


        die('DIE CALLED AT LINE: ' . __LINE__ . ' in FILE: ' . __FILE__);   
    }
}

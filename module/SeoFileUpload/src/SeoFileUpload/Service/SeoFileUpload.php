<?php

namespace SeoFileUpload\Service;

use SeoFileUpload\Model\File as FileModel;

use Phoenix\Service\ServiceAbstract;
use \ListModule\Model\ListItem;
class SeoFileUpload extends \ListModule\Service\Lists
{
    const CREATE_IMAGE_CROP = 1;
    const CREATE_IMAGE_SCALE = 0;

    const MEDIA_MANAGER_IMAGE_TYPES = 'jpg|jpeg|gif|png|bmp|tif|tiff';
    const MEDIA_MANAGER_SOUND_TYPES = 'mp3|wma';
    const MEDIA_MANAGER_VIDEO_TYPES = 'swf|flv|mp4|mpga|mpg|mpeg|wmv|mov';
    const MEDIA_MANAGER_DOC_TYPES = 'pdf|xls|doc|docx|rtf|ods|txt|csv|ppt|zip|xml';
    const THUMBS_FOLDER_PREFIX = '__thumbs';
    const MEDIA_MANAGER_IMAGE_QUALITY = 9.5;

    protected $imageManipulation;

    protected $genericTypesImages = array(
            'crop' => self::CREATE_IMAGE_CROP,
            'scale' => self::CREATE_IMAGE_SCALE
        );

    protected $userBasePaths = array();
    public $_action = ''; //to identify user action for filtering items  
 

    /**
     * RULES for base paths
     * If the userBasePaths array is blank, all site directories are available for use.
     * If $user is super user (type = 1), the array is left blank
     * If $user isCorporate, the array is left blank
     * Otherwise, only the currentProperty's directory and the corporateProperty's directory are accessible
     */
    public function setUserBasePaths($currentProperty, $corporateProperty, $user, $basePath = '')
    {
        if ($user->getIsCorporate() == 1) {
            return true;
        }

        $currentPropertyCode = $corporatePropertyCode = null;

        /**
         * TODO: we need to move this logic somewhere else
         */
        if ( is_object($currentProperty) ) $currentPropertyCode = $currentProperty->getCode();
        if ( is_object($corporateProperty) ) $corporatePropertyCode = $corporateProperty->getCode();

        $this->userBasePaths[] = SITE_PATH . '/' . $basePath . $currentPropertyCode;
        $this->userBasePaths[] = SITE_PATH . '/' . $basePath . $corporatePropertyCode;
    }

    public function getUserBasePaths()
    {
        return $this->userBasePaths;
    }

    public function createPathTree($path)
    {
        $pathTree = array();
        if ($path == '') {
            return array();
        }

        $pathTreeArray = explode('/', $path);

        //$pathTreeArray = array_reverse($pathTreeArray);

        do {
            array_pop($pathTreeArray);
            $treeCount = count($pathTreeArray);
            if ($treeCount == 0) {
                break;
            }
            $lastKey = $treeCount - 1;
            $pathTree[$pathTreeArray[$lastKey]] = implode('/', $pathTreeArray);
        } while (count($pathTreeArray) > 1);       


        $pathTree['Media Home'] = '/';

        $pathTree = array_reverse($pathTree);

        return $pathTree;
    }
     public function createModel($entity = null)
    {
        $model = new FileModel($this->getConfig());
        $model->setDefaultEntityManager($this->getDefaultEntityManager());

        if ($entity) {
            $model->setEntity($entity);
        }

        return $model;
    }

    public function isValidPath($mediaPath)
    {

        if (empty($this->userBasePaths)) {
            return true;
        }

        foreach ($this->userBasePaths as $valBasePath) {
            if (strpos($mediaPath, $valBasePath) !== false) {
                return true;
            }
        }

        return false;
    }

    public function isValidFileType($fileName, $acceptTypes = 'IMAGE,SOUND,VIDEO,DOC')
    {
        $acceptedTypes = '|';

        foreach(explode(',', $acceptTypes) as $type) {
            $acceptedTypes .= constant('self::MEDIA_MANAGER_' . $type .'_TYPES').'|';
        }
        
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        return mb_strpos($acceptedTypes, "|$ext|") !== false;
    }

    public function formatFileName($origName)
    {
        $name = str_replace(' ', '_', $origName);

        //Replace ereg functions with preg functions for php5.3
        //Check if file name have other non-alphanumeric characters apart from underscore, full stop (period), hyphen-minus, plus 
        if(!preg_match('/^[A-Za-z0-9_\.\-\+]+$/', $name))
        {    
            $md5       = mb_substr(md5($name), 0, 6);
            //Remove other non-alphanumeric characters apart from underscore, full stop (period), hyphen-minus, plus from name
            $name      = preg_replace('/[^A-Za-z0-9_\.\-\+]+/', "", $name);
            $lastDot = mb_strrpos($name,'.');
            //Insert md5 hash in name to preserve uniqueness of file name and Prepended the md5 hash with an underscore( so that the resulting filename can be easy to read )
            $name    = mb_substr($name, 0, $lastDot) .'_'. $md5  . mb_substr($name, $lastDot);
        }

        return $name;
    }
    public function removeThumbs($name, $path)
    {
       // echo "deleting from Service\SeoFileUpload removeThumbs name:$name , path: $path<br>";
        $seoFileUploadSizes = $this->getConfig()->get(array('images', 'seoFileUploadSizes'), array());

        foreach ($seoFileUploadSizes as $size) {
            $thumbFile = $path . "/{$size['folder']}/$name";

            if (file_exists($thumbFile)) {
               // echo "unlinking file: $thumbFile<br>";
                unlink($thumbFile);
            }else{
                //echo "not unlinking file: $thumbFile<br>";
            }
        }
    }
    /**
     * 
     * @param type $name
     * @param type $path
     */
    public function removeFile($name, $path){
        $fullPath = $path . "/" . $name;
        if (file_exists($fullPath)) {
               // echo "unlinking file from removeFile: $fullPath<br>";
                unlink($fullPath);
            }else{
                //echo "not unlinking file from removeFile: $fullPath<br>";
            }
        
    }
    
    /**
     * NOTE: Right now this only takes simple parameters
     * @param  array $parameters
     * @param  array $orderBy
     * @return array
     */
    public function getFilesBy($parameters = array(), $orderBy = array(), $limit = 0)
    {
        $getByQuery = $this->getDefaultEntityManager()->createQueryBuilder();

        $getByQuery->select('mmf')
                   ->from('SeoFileUpload\Entity\SeoFileUploadFiles', 'mmf')
                   ->where('mmf.status = 0');

        foreach ($parameters as $keyParam => $valParam) {
            $parameterName = ":$keyParam";
            $getByQuery->andWhere("mmf.$keyParam = $parameterName");
            $getByQuery->setParameter($keyParam, $valParam);
        }

        foreach ($orderBy as $keyOrder => $valOrder) {
            $getByQuery->orderBy("mmf.$keyOrder", $valOrder);
        }

        if ($limit > 0) {
            $getByQuery->setMaxResults($limit);
        }

        $result = $getByQuery->getQuery()->getResult();

        $files = array();

        if (!empty($result)) {
            foreach ($result as $valResult) {
                $files[] = $this->createModel($valResult);
            }
        }

        return $files;
    }

    public function getItemBy($parameters = array())
    {
       // echo "SEO Service: Getting Item by id <br/>";
        $result = $this->getDefaultEntityManager()->getRepository('SeoFileUpload\Entity\SeoFileUploadFiles')->findOneBy($parameters);

        return is_null($result) ? null : $this->createModel($result);
    }

    public function getItems()
    {
        
       
        switch ($this->_action){
            
            case 'editlist':
                $type='text/xml';
                break;
            case 'robotsFile':
                $type = 'text/plain';
        } 
        
        $files = $this->getDefaultEntityManager()->getRepository('SeoFileUpload\Entity\SeoFileUploadFiles')->findBy(array('type'=>$type));
        
        return $files;
    }

   


    public function getFiles($filePath)
    {
        $directories = array();
        $files = array();

        $directoryIterator = new \DirectoryIterator($filePath);

        foreach ($directoryIterator as $valFile) {
            $fileInfo = array();

            if ($valFile->isDot()) {
                continue;
            }

            $fileInfo['path'] = $valFile->getPathName();
            $fileInfo['fileName'] = $valFile->getFileName();
            $fileInfo['directory'] = ($valFile->isDir()) ? true : false;

            if ($fileInfo['directory']) {
                $directories[] = $fileInfo;
            } else {
                $files[] = $fileInfo;
            }
        }

        return array_merge($directories, $files);
    }

    public function save($fileModel, $mediaData, $savePath = null)
    {
        //@TODO make this so files that aren't images can be saved.
        if (!$fileModel instanceof File) {
            $fileModel = $this->createModel();
        }

        $fileModel->exchangeArray($mediaData);
        $fileModel->saveFile($mediaData['tmpName'], $savePath);
    	$fileModel->save();

        return $fileModel;
    }
     
}
<?php
namespace MediaManager\Service;

use MediaManager\Model\File as FileModel;
use Phoenix\Service\ServiceAbstract;
use Phoenix\StdLib\FileHandling;
use MediaManager\Classes\Preview;
use MediaManager\Classes\MediaFiles;

class MediaManager extends \ListModule\Service\Lists
{
    const CREATE_IMAGE_CROP           = 1;
    const CREATE_IMAGE_SCALE          = 0;
    const MEDIA_MANAGER_IMAGE_TYPES   = 'jpg|jpeg|gif|png|bmp|tif|tiff';
    const MEDIA_MANAGER_SOUND_TYPES   = 'mp3|wma';
    const MEDIA_MANAGER_VIDEO_TYPES   = 'swf|flv|mp4|mpga|mpg|mpeg|wmv|mov';
    const MEDIA_MANAGER_DOC_TYPES     = 'pdf|xls|doc|docx|rtf|ods|txt|csv|ppt|zip|xlsx|sltx|potx|ppsx|pptx|sldx|dotx';
    const THUMBS_FOLDER_PREFIX        = '__thumbs';
    const MEDIA_MANAGER_IMAGE_QUALITY = 9.5;
    const FOLDER_PERMISSIONS          = 0755; // must be an octal value
    const ACCEPTED_CATEGORIES         = 'IMAGE,DOC';

    protected $imageManipulation;
    protected $mmImageService;
    protected $allowCas           = true;
    protected $userBasePaths      = array();
    protected $genericTypesImages = array(
        'crop'  => self::CREATE_IMAGE_CROP,
        'scale' => self::CREATE_IMAGE_SCALE
    );

    private $acceptedFileTypes = '';

    /**
     * attachFiles
     * @param  Array $options - specify what the options can be
     * @return Array          [description]
     */
    public function attachFiles($options)
    {
        $responseAttachments = array();
        $this->setImageManipulation($this->getServiceManager()->get('phoenix-imagemanipulation'));
        $this->setConfig($this->getServiceManager()->get('mergedConfig'));

        $attachedMedia = $this->getServiceManager()->get('phoenix-attachedmediafiles');
        $attachedMedia->setMediaManager($this);
        $attachedMedia->setParentInfo($options);

        if (!empty($options['attachments']) && is_array($options['attachments'])) {
            foreach ($options['attachments'] as $fileId => $orderNumber) {
                $fileId      = (int) $fileId;
                $orderNumber = (int) $orderNumber;

                if (!$attachedMedia->fileIsAttached($fileId)) {

                    // parentItemId will be set for save, but not for preediting a new item
                    // when an item is new, we just want to send back info without saving

                    if ($options['parentItemId']) {
                        $attachment = $attachedMedia->attachFile($fileId, $orderNumber);                     
                        $sizes      = $this->getModuleThumbSizes($options['parentModule']);
                        $attachedFile = new MediaFiles\AttachedFile(array(
                            'name'     => $attachment->getName(),
                            'savePath' => SITE_PATH . $attachment->getPath() . '/'
                        ));
                        $this->createThumbsForImage($attachedFile, $sizes);
                    } else {
                        $attachment = null;
                    }

                    //if ($this->shouldUpdateClientUI()) {
                        $responseAttachment = $this->getResponseAttachment($fileId, $options, $attachment);
                        array_push($responseAttachments, $responseAttachment);
                    // }
                }
            }
        }

        return $responseAttachments;     
    }

    private function shouldUpdateClientUI()
    {
        $shouldUpdateClientUI = true;
        $module = $this->getDefaultEntityManager()
                       ->getRepository('Toolbox\Entity\Components')
                       ->findOneBy(array('name' => 'AttachedMediaFiles'));

        if ($module->getCasAllowed() && $module->getCasEnabled()) {
            $shouldUpdateClientUI = false;
        }

        return $shouldUpdateClientUI;
    }

    private function getModuleThumbSizes($moduleName)
    {
        $config      = $this->getServiceManager()->get('MergedConfig');
        $moduleSizes = $config->get(array('media-manager-sizes', $moduleName), $config->get(array('media-manager-sizes', 'dynamicListModules'))); 
        $mmSizes     = $config->get(array('images', 'mediaManagerSizes'));

        $sizesForAttachment = array();

        foreach ($moduleSizes as $sizeName) {
            $sizesForAttachment[$sizeName] = $mmSizes[$sizeName];
        } 

        return $sizesForAttachment;
    }

    public function setImageManipulation($imageManipulation)
    {
        $this->imageManipulation = $imageManipulation;
    }

    public function setImageService($mmImageService)
    {
        $this->mmImageService = $mmImageService;     
    }

    /**
     * RULES for base paths
     * If the userBasePaths array is blank, all site directories are available for use.
     * If $user is super user (type = 1), the array is left blank
     * If $user isCorporate, the array is left blank
     * Otherwise, only the currentProperty's directory and the corporateProperty's directory are accessible
     */
    public function setUserBasePaths($currentProperty, $corporateProperty, $user, $basePath = 'd/')
    {
        if ($user->isCorporate() == 1) {
            return true;
        }

        $currentPropertyCode = $corporatePropertyCode = null;

        if (is_object($currentProperty)) {
            $currentPropertyCode = $currentProperty->getCode();
            $propertyRoot = $this->getConfig()->get('propertyMediaRoot', $currentProperty->getUrl());
        } else {
            $propertyRoot = $this->getConfig()->get('propertyRoot');
        }

        if (is_object($corporateProperty)) {
            $corporatePropertyCode = $this->getConfig()->get('corporateMediaRoot', $corporateProperty->getCode());
        }


        if ($propertyRoot) {
            $this->userBasePaths[] = SITE_PATH . '/' . $basePath . $propertyRoot;
        }

        $this->userBasePaths[] = SITE_PATH . '/' . $basePath . $currentPropertyCode;
        $this->userBasePaths[] = SITE_PATH . '/' . $basePath . $corporatePropertyCode;
    }

    public function getUserBasePaths()
    {
        return $this->userBasePaths;
    }

    public function isValidPath($mediaPath)
    {
        $isValidPath = false;

        if (empty($this->userBasePaths)) {
            $isValidPath = true;
        } else {
            foreach ($this->userBasePaths as $valBasePath) {
                if (strpos($mediaPath, $valBasePath) !== false) {
                    $isValidPath = true;
                    break;
                }
            }
        }

        return $isValidPath;
    }

    private function setAcceptedFileTypes($acceptTypes = self::ACCEPTED_CATEGORIES)
    {
        $this->acceptedFileTypes = $this->processTypes($acceptTypes);
    }

    private function processTypes($acceptTypes)
    {
        $acceptedTypes = '|';
        foreach (explode(',', $acceptTypes) as $type) {
            $acceptedTypes .= constant('self::MEDIA_MANAGER_' . $type . '_TYPES') . '|';
        }

        // no zip files for non developers
        if (!$this->isDeveloper()) {
            $acceptedTypes = str_replace('zip', '', $acceptedTypes);
        }
        return $acceptedTypes; 
    }

    private function isDeveloper()
    {
        $currentUser = $this->serviceManager->get('phoenix-users-current');
        return ($currentUser->getUserEntity()->getType() === 2);
    }

    private function isValidFileType($fileName)
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return mb_strpos($this->acceptedFileTypes, "|$ext|") !== false;
    }

    public function addImageMeta($imageArray)
    {
        $em = $this->getDefaultEntityManager();

        $entity = new \MediaManager\Entity\MediaManagerImage;
        $entity->fromArray($imageArray);
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Creates thumbs for a filename at a path, given a set of size config options
     *
     * For the sizes:
     * - Examples of keys are Thumb, Small, Medium, Large, CmlpGroup, Accomodations, Default
     * - $sizeConfig contains the values of type, width, height, folder (i.e. folder name)
     * 
     * @param  String $directory         
     * @param  String $fileName
     * @param  Array $sizes 
     */
    public function createThumbsForImage($file, $sizes = array())
    {
        if ($file->getGeneralType() === 'IMAGE') {
            foreach ($this->getSizes($sizes) as $key => $sizeConfig) {
                if (!$this->isRecognizedType($sizeConfig)) continue;
                $this->createThumb($file->getSavePath(), $file->getName(), $sizeConfig);
            }
        }
    }

    private function shouldHideFolder($folderPath)
    {
        $shouldHideFolder = false;

        if (strpos($folderPath, '__thumbs') !== false
            || $this->isOriginals($folderPath)
            || !$this->isValidPath($folderPath)
            || !$this->isDirectory($folderPath)) {
            $shouldHideFolder = true;
        }

        return $shouldHideFolder;
    }

    private function isOriginals($folderPath)
    {
        $folderName = basename($folderPath);
        $isOriginals = ($folderName === 'originals');
        return $isOriginals;
    }

    private function isDirectory($path)
    {
        return is_dir($path);
    }

    public function createModel($entity = null)
    {
        $model = new FileModel($this->getConfig());
        $model->setDefaultEntityManager($this->getDefaultEntityManager());
        $model->setLanguages($this->getServiceManager()->get('phoenix-languages'));
        
        if ($entity) {
            $model->setEntity($entity);
        }

        return $model;
    }

    private function getUnzippedFileOptions($fileName, $filePath, $options)
    {
        return array(
            'mediaRoot'  => $this->getMediaRoot(),
            'origName'   => $fileName,
            'name'       => $fileName,
            'path'       => $this->formatZipPath($filePath),
            'tmpName'    => $fileName,
            'propertyId' => $options['propertyId'],
            'userId'     => (int) $options['userId']
        );
    }

    private function formatZipPath($path)
    {
        if (substr($path, 0, 3) == '/d/') {
            $path = substr($path, 3);
        }
        return $path;
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
                ->from('MediaManager\Entity\MediaManagerFiles', 'mmf')
                ->where('mmf.status = 1');

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
        $result = $this->getDefaultEntityManager()->getRepository('MediaManager\Entity\MediaManagerFiles')->findOneBy($parameters);
        return is_null($result) ? null : $this->createModel($result);
    }

    public function getItems()
    {
        $files = $this->getDefaultEntityManager()->getRepository('MediaManager\Entity\MediaManagerFiles')->findBy(array());
    }

    public function addNewFolder($path, $name)
    {
        if (!$this->makeDirectory($this->getNewFolderSeparator($path, $name))) {
            throw new \Exception('Folder already exists');
        }
        $options                    = $this->getPreviewOptions();
        $options['parentDirectory'] = $this->getMediaRoot() . $path;
        $options['mediaFile']       = false;
        $options['previewType']     = 'FOLDER';
        $options['currentPath']     = $path;
        $options['entry']           = $name;
        return Preview\Factory::build($options)->toArray();
    }

    private function getNewFolderSeparator($path, $name)
    {
        $separator = $path ? '/' : '/d/';
        return SITE_PATH . $separator . $path . $name;        
    }

    public function deleteFolder($path)
    {
        $responseData;
        $deletePath = SITE_PATH . '/' . $this->getConfig()->get('mediaRoot') . $path;

        if (!file_exists($deletePath)) {
            throw new \Exception('500 Internal Server Error');
        } else if (mb_strpos($deletePath, '/..') !== false) {
            throw new \Exception('500 Internal Server Error');
        } else {
            $this->removeSubdirectories($deletePath);
            rmdir($deletePath);
        }

        return $path;
    }

    private function removeSubdirectories($deletePath)
    {
        $subdirectories = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($deletePath), \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($subdirectories as $file) {
            if (in_array($file->getBasename(), array('.', '..'))) {
                continue;
            } elseif ($file->isDir()) {
                rmdir($file->getPathname());
            } elseif ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            }
        }
    }

    private function unlinkFile($filePath, $fileName)
    {
        $isUnlinked = true;
        if (!@unlink(SITE_PATH . "$filePath/$fileName")) {
            $isUnlinked = false;
        }
        return $isUnlinked;
    }

    private function removeThumbs($filePath, $fileName)
    {
        foreach (glob(SITE_PATH . "$filePath/__thumbs*", GLOB_ONLYDIR) as $dir) {
            @unlink("$dir/$fileName");
        }
    }

    public function deleteFiles($filePath, $fileName)
    {
        if ($this->unlinkFile($filePath, $fileName)) {
            $this->removeThumbs($filePath, $fileName);
        }
    }

    public function deleteFile($fileId)
    {
        $mediaFile = $this->getItemBy(array('id' => $fileId));

        if (!$mediaFile) {
            throw new \Exception('500 Internal Server Error');
        }

        $qBldr = $this->getDefaultEntityManager()->createQueryBuilder();

        $this->setAcceptedFileTypes('IMAGE');
        if ($this->isValidFileType($mediaFile->getName())) {
            $res = $qBldr->delete('MediaManager\Entity\MediaManagerImage', 'mmi')
                         ->where("mmi.fileId=:fileId")
                         ->setParameter('fileId', $fileId)
                         ->getQuery()
                         ->execute();
        }

        // Delete associated db entries for this file
        $res = $qBldr->delete('MediaManager\Entity\MediaManagerFiles', 'mmf')
                     ->where("mmf.id=:fileId")
                     ->setParameter('fileId', $fileId)
                     ->getQuery()
                     ->execute();

        $imagesFolder = $mediaFile->getPath();
        $this->deleteFiles($imagesFolder, $mediaFile->getName());
        $mediaFile->delete();

        return true;
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

    private function createFile($fileModel, $mediaData, $savePath)
    {
        $fileModel->exchangeArray($mediaData);
        $fileModel->saveFile($mediaData['tmpName'], $savePath);
    }

    private function saveFile($fileModel, $mediaData, $approved = false)
    {
        $mediaData['path'] = str_replace(SITE_PATH, '', $mediaData['path']);
        parent::save($fileModel, $mediaData, $approved);
        return $fileModel;
    }

    private function createModelForFile($fileModel)
    {
        if (!$fileModel instanceof File) {
            $fileModel = $this->createModel();
        }

        return $fileModel;
    }

    public function save($fileModel, $mediaData, $approved = false , $savePath = null)
    {
        $fileModel = $this->createModelForFile($fileModel);
        $this->createFile($fileModel, $mediaData, $savePath);
        $this->saveFile($fileModel, $mediaData, $approved);
        return $fileModel;
    }

    public function getFileList()
    {
        $fileList   = array();
        $mediaFiles = $this->getFilesBy();
        $options    = $this->getPreviewOptions();

        $this->setAcceptedFileTypes();

        foreach ($mediaFiles as $mediaFile) {
            // retrieve files that only exist both in db and on filesystem
            if (file_exists(SITE_PATH . $mediaFile->getPath() . '/' . $mediaFile->getName())) {
                $this->updateFileList($mediaFile, $options, $fileList);
            }
        }
        
        return $fileList;
    }

    private function updateFileList($mediaFile, $options, &$fileList)
    {
        $options['mediaFile']   = $mediaFile;
        $options['entry']       = $mediaFile->getName();
        $options['currentPath'] = substr($mediaFile->getPath() . '/', 3) ?: ''; // remove media root from current path

        $file = $this->getFile($options);
        if ($file) {
            $fileList[] = $file;
        }
    }

    public function getFolders()
    {
        $root = $this->getMediaRoot();

        // get iterator
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
            \RecursiveIteratorIterator::CATCH_GET_CHILD
        );

        // get paths
        $paths = array($this->getRelativePath($root));
        foreach ($iterator as $path => $directory) {

            //$name = pathinfo($valFolder, PATHINFO_BASENAME);

            if ($this->shouldHideFolder($path)) {
                continue;
            }
            // remove site path, we want relative path
            $path = $this->getRelativePath($path);
            $paths[] = str_replace('\\', '/', $path . '/');
        }

        return $paths;
    } 

    private function getRelativePath($path)
    {
        $prefix = SITE_PATH . '/';
        return substr($path, strlen($prefix));    
    }

    /**
     * Expected Options:
     * - mediaManagerImageHREF, siteRoot, mediaRoot from 'getPreviewOptions'
     * - also expects entry, currentPath, mediaFile, fileId
     */
    private function getFile($options)
    {
        $options = array_merge($options, $this->getPreviewOptions());
        $file    = false;

        if ($this->isValidFileType($options['entry'])) {
            $options['previewType'] = $this->getPreviewType($options['entry']);
            $file = Preview\Factory::build($options)->toArray();
        }

        return $file;
    }

    private function getResponseAttachment($fileId, $options, $attachedFile = null)
    {
        $id = $attachedFile ? $attachedFile->getId() : $fileId;
        $options = array(
            'parentItemId' => $options['parentItemId'],
            'parentModule' => $options['parentModule'],
            'entry'        => $this->getFileName($fileId),
            'currentPath'  => $this->getImagePath($fileId),
            'fileId'       => $id
        );
        
        $options['previewType'] = $this->getPreviewType($options['entry']);
        return Preview\Factory::build($options)->toArray();
    }

    public function getFolder($path, $folderName)
    {
        $mediaRoot = $this->getMediaRoot();
        $fullPath = $this->getMediaRoot() . $path;
        $options = $this->getPreviewOptions();
        $options['parentDirectory'] = $fullPath;
        $options['mediaFile'] = false;
        $options['previewType'] = 'FOLDER';
        $options['currentPath'] = $path;
        $options['entry'] = $folderName;
        return Preview\Factory::build($folderOptions)->toArray();
    }

    public function getPreviewOptions()
    {
        $options = array();
        $options['mediaManagerImageHREF'] = $this->getConfig()->get(array('paths', 'toolboxIncludeUrl'))
            . 'module/MediaManager/view/media-manager/images/';
        $options['siteRoot'] = $this->getConfig()->get(array('templateVars', 'siteroot'));
        $options['mediaRoot'] = $this->getMediaRoot();
        return $options;
    }

    private function getPreviewType($fileName)
    {
        $fileCategory = '';
        $ext          = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        foreach (explode(',', self::ACCEPTED_CATEGORIES) as $type) {
            $types = '|' . constant('self::MEDIA_MANAGER_' . $type . '_TYPES') . '|';
            if (mb_strpos($types, "|$ext|") !== false) {
                $fileCategory = $type;
            }
        }

        if (empty($fileCategory)) {
            throw new \Exception('Accepted Category Not Found');
        }

        return $fileCategory;
    }

    private function getSizes(Array $sizes)
    {
        if (empty($sizes)) {
            $sizes = $this->getConfig()->get(array('images', 'defaultSizes'), array());
        }
        return $sizes;
    }

    private function isRecognizedType($sizeConfig)
    {
        if (!isset($this->genericTypesImages[$sizeConfig['type']])) {
            trigger_error("Unrecognized image type '{$sizeConfig['type']}' in mediaManager module conf, should be crop or scale", E_USER_ERROR);
            return false;
        }     
        return true;
    }

    private function makeDirectory($path)
    {
        $madeDirectory = false;
        if (!is_dir($path)) {
            mkdir($path, self::FOLDER_PERMISSIONS);
            $madeDirectory = true;
        }
        return $madeDirectory;
    }

    private function buildThumbFile($directory, $fileName, $sizeConfig)
    {
        return $this->createThumbDirectory($directory, $sizeConfig) . "/" . $fileName;
    }

    private function createThumbDirectory($directory, $sizeConfig)
    {
        $thumbPath = $directory . $sizeConfig['folder'];
        $this->makeDirectory($thumbPath);
        return $thumbPath;
    }

    private function createThumb($directory, $fileName, $sizeConfig)
    {
        $fileName  = $this->replaceSpacesWithUnderscores($fileName);
        $thumbFile = $this->buildThumbFile($directory, $fileName, $sizeConfig);
        $this->createFromExistingImage($thumbFile, $sizeConfig['width'], $sizeConfig['height'], $directory.$fileName);
    }

    private function replaceSpacesWithUnderscores($someString)
    {
        return str_replace(' ', '_', $someString);
    }

    private function getImageResizeOptions($image, $width, $height)
    {
        list($parentWidth, $parentHeight) = getimagesize($image);
        if ($width / $height > $parentWidth / $parentHeight) {
            $options = array(
                'width' => $width,
                'heightRatio' => $width / $parentWidth
            );
        } else {
            $options = array(
                'height' => $height,
                'widthRatio' => $height / $parentHeight
            );
        }
        return $options;
    }

    private function getThumbCropOptions($width, $height)
    {
        return array(
            'width' => $width,
            'height' => $height,
            'centerLeft' => 0.5,
            'centerTop' => 0.5       
        );
    }

    private function createFromExistingImage($newImage, $width, $height, $existingImage)
    {
        try {
            $this->checkImageManipulation();
            $this->imageManipulation->load($existingImage);
            $this->imageManipulation->resize($this->getImageResizeOptions($existingImage, $width, $height));
            $this->imageManipulation->crop($this->getThumbCropOptions($width, $height));
            $this->imageManipulation->save($newImage);
        } catch (Exception $e) {
            trigger_error('Problem trying to create crop of image: ' . $e->getMessage());
        }
    }

    private function checkImageManipulation()
    {
        if (!$this->imageManipulation) {
            throw new \Exception('Image Manipulation is undefined');
        }
    }

    public function getMediaRoot()
    {
        return SITE_PATH . '/' . $this->getConfig()->get('mediaRoot');
    }

    private function getImagePath($fileId)
    {
        return $this->getPath($fileId) . $this->getAttachmentThumbFolder();
    }

    public function getPath($fileId)
    {
        return $this->getSiteRoot() . $this->getFilePath($fileId);
    }

    public function getFileName($fileId)
    {
        $fileInfo = $this->getFilesBy(array('id' => $fileId), array('name' => 'ASC'));
        if (!$fileInfo) {
            throw new \Exception('Bad fileId');
        }
        return $fileInfo[0]->getName();
    }

    private function getSiteRoot()
    {
        return $this->getConfig()->get(array('templateVars', 'siteroot'), '/');
    }

    private function getFilePath($fileId)
    {
        $fileInfo = $this->getFilesBy(array('id' => $fileId), array('name' => 'ASC'));
        if (!$fileInfo) {
            throw new \Exception('Bad fileId');
        }
        return ltrim($fileInfo[0]->getPath() . '/', '\/');
    }

    private function getAttachmentThumbFolder()
    {
        $sizes = $this->getConfig()->get(array('images', 'defaultSizes'), array());
        return $sizes['Small']['folder'] . '/';
    }

    public function updateUploadedFile($fileItem, $uploadedFile)
    {
        $fileItem->saveFile($uploadedFile->getTempName(), $uploadedFile->getSavePath());
        $fileItem->getEntity()->setModified(new \DateTime());
        $fileItem->save();
    }

    private function fileAlreadyExists($fileItems)
    {
        return count($fileItems);
    }

    private function removeImageThumbs($file)
    {
        if ($file->getGeneralType() === 'IMAGE') {
            $this->removeThumbs($file->getSavePath(), $file->getName());
        }
    }

    public function unzipFiles($fileId, $options)
    {
        $this->setConfig($this->getServiceManager()->get('mergedConfig'));
        $this->setImageManipulation($this->getServiceManager()->get('phoenix-imagemanipulation'));
        $this->setImageService($this->getServiceManager()->get('phoenix-mediamanager-image'));
        $this->setAcceptedFileTypes();

        $responseData    = array();
        $fileInfo        = $this->getFilesBy(array('id' => $fileId), array('name' => 'ASC'), 1);
        $path            = $fileInfo[0]->getPath() . '/';
        $fullPath        = SITE_PATH . $path . $fileInfo[0]->getName();
        $destinationPath = pathinfo(realpath($fullPath), PATHINFO_DIRNAME);
        $zip             = new \ZipArchive;

        if ($zip->open($fullPath)) {
            $numberOfZippedFiles = $zip->numFiles;

            for ($i=0; $i < $numberOfZippedFiles; $i++) {

                $entry = $zip->getNameIndex($i);

                // skip folders
                if (substr($entry, -1) == '/') {
                    continue;
                }

                $fp  = $zip->getStream($entry);
                $ofp = fopen($destinationPath . '/' . basename($entry), 'w');

                if (!$fp) {
                    throw new Exception('Unable to extract the file');
                }

                while (!feof($fp)) {
                    fwrite($ofp, fread($fp, 8192));
                }

                fclose($fp); 
                fclose($ofp);

                $fileModel = $this->createModelForFile(null);
                $unzippedFileOptions = $this->getUnzippedFileOptions(basename($entry), $path, $options);
                $unzippedFile = new MediaFiles\UnzippedFile($unzippedFileOptions);

                $fileItem = $this->saveFile($fileModel, $unzippedFile->getSaveData());
                $this->updateImagesDb($unzippedFile, $fileItem);
                $this->createThumbsForImage($unzippedFile);
                $responseOptions = $unzippedFile->getResponseOptions($fileItem);
                $responseData[] = $this->getFile($responseOptions);
            }

            $zip->close();
            $this->deleteFile($fileId);
        } else {
            throw new \Exception('500 Internal Server Error');
        }

        return $responseData;
    }

    private function updateImagesDb($file, $fileItem)
    {
        if ($file->getGeneralType() === 'IMAGE') {
            $mmImage      = $this->mmImageService->createModel();
            $imageOptions = $file->getImageOptions($fileItem->getId());
            $this->mmImageService->save($mmImage, $imageOptions);
        }
    }

    public function upload($file)
    {
        $this->setConfig($this->getServiceManager()->get('mergedConfig'));
        $this->setImageManipulation($this->getServiceManager()->get('phoenix-imagemanipulation'));
        $this->setImageService($this->getServiceManager()->get('phoenix-mediamanager-image'));
        $this->setAcceptedFileTypes();

        if (!$this->isDeveloper() && $file->getType() === 'application/zip') {
            throw new \Exception('403 Forbidden');
        }

        $responseData = array();

        if ($file->isOK($this->getConfig()->get('mediaManager')) && $this->isValidFileType($file->getName())) {

            $fileItems = $this->getFilesBy($file->getFileParameters(), array(), 1);

            if ($this->fileAlreadyExists($fileItems)) {
                $fileItem = $fileItems[0];
                $this->removeImageThumbs($file);
                $this->updateUploadedFile($fileItem, $file);
            } else {
                $fileItem = $this->save(null, $file->getSaveData());
                $this->updateImagesDb($file, $fileItem);
            }

            $this->createThumbsForImage($file); // this also takes a second arg, we can send in module specific thumbs here
            $responseOptions = $file->getResponseOptions($fileItem);
            $responseData    = $this->getFile($responseOptions);

        } else {
            throw new \Exception('500 Internal Server Error');
        }

        return $responseData;
    }
}

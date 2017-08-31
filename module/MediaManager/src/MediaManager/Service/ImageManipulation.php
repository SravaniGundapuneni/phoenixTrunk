<?php
namespace MediaManager\Service;

use MediaManager\Model\File as FileModel;
use Phoenix\Service\ServiceAbstract;

class ImageManipulation extends ServiceAbstract
{
    const LIBRARY_IMAGE_MAGICK = 'Phoenix\ImageManipulation\ImageMagick';
    const LIBRARY_GD2 = 'Phoenix\ImageManipulation\Gd2';
    const MAX_CROP_SIZE = 3000;

    protected $imageLibrary;
    protected $width; 
    protected $height;
    protected $format;    

    public function setImageLibrary($library = self::LIBRARY_IMAGE_MAGICK)
    {
        $this->imageLibrary = new $library;
    }

    public function load($sourceImageFile)
    {
        if (file_exists($sourceImageFile)) {
            $this->imageLibrary->load($sourceImageFile);
            list($this->width, $this->height) = getimagesize($sourceImageFile);
        } else {
            throw new \Exception('Source image file not found');
        }
    }

    public function save($targetImageFile)
    {
        $this->imageLibrary->setFormat(strtolower(pathinfo($targetImageFile, PATHINFO_EXTENSION)), MediaManager::MEDIA_MANAGER_IMAGE_QUALITY);
        $this->imageLibrary->save($targetImageFile);
    }

    public function resize($params)
    {
        if (isset($params['ratio'])) {
            $this->width *= $params['ratio'];
            $this->height *= $params['ratio'];
        } else {
            $this->width = isset($params['width']) ? $params['width'] : $params['widthRatio'] * $this->width;
            $this->height = isset($params['height']) ? $params['height'] : $params['heightRatio'] * $this->height;
        }
        
        $maxCropSize = $this->getConfig()->get(array('images', 'maxCropSize'), static::MAX_CROP_SIZE);

        if ($this->width > $maxCropSize) {
            $this->width = $maxCropSize;
        }
        
        if ($this->height > $maxCropSize) {
            $this->height = $maxCropSize;
        }
        
        $this->imageLibrary->resize((int)$this->width, (int)$this->height);
    }

    public function crop($params)
    {
        $maxCropSize = $this->getConfig()->get(array('images', 'maxCropSize'), static::MAX_CROP_SIZE);
        
        if ($this->width > $maxCropSize) {
            $this->width = $maxCropSize;
        }
        
        if ($this->height > $maxCropSize) {
            $this->height = $maxCropSize;
        }

        $width = isset($params['width']) ? $params['width'] : $params['widthRatio'] * $this->width;
        $height = isset($params['height']) ? $params['height'] : $params['heightRatio'] * $this->height;
        $offsetLeft = isset($params['offsetLeft']) ? $params['offsetLeft'] : 
            $params['centerLeft'] * $this->width - $width/2;
        $offsetTop = isset($params['offsetTop']) ? $params['offsetTop'] : 
            $params['centerTop'] * $this->height - $height/2;
        
        $this->imageLibrary->crop($offsetLeft, $offsetTop, $width, $height);
         
        $this->width = $width;
        $this->height = $height;

        //[Anton says: so the crop itself doesn't obey the max crop size, but in the following code
        // this object now sets its instance variables to obey them.  Seems broken to me!  Seems
        // like this code should be higher up.]
    }
}
<?php

class image
{
    const JPEG_QUALITY = 100;
    const PNG_QUALITY = 9;

    /**
     * image location including filename.
     * example: /uploads/images/my_folder/my_image.jpg
     * @var
     */
    private $imageUrl;
    /**
     * filename of image including file type.
     * example: my_image.jpg
     * @var
     */
    private $fileName;
    /**
     * ckfinder folder name image is located in including slashes.
     * example: my_folder/
     * @var
     */
    private $folderName;
    /**
     * store image dimensions for initial setup of window.
     * @var
     */
    private $imageDimensions = array();
    /**
     * set base directory
     * @var
     */
    private $baseDir;
    /**
     * initial value for $_post error
     * @var
     */
    private $postError = true;

    private $ext = '';

    private static $variablesToInitialize = array('imageUrl', 'fileName', 'folderName');

    public function __construct()
    {
        $this->initializeBaseDirectory();
        $this->initializeVariables();
        $this->initializeImageDimensions();
        $this->initializeFileExtension();
    }

    public function getBaseDir()
    {
        return $this->baseDir;
    }

    public function isPosted()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($this->imageUrl) && !empty($this->fileName)) {
            $this->postError = false;
            return true;
        }
        return false;
    }

    public function resize()
    {
        if ($this->postError) {
            return false;
        }

        // check if file exists?

        // do we want to save originals in the originals folder?

        $targ_w = $_POST['w'];
        $targ_h = $_POST['h'];

        // this feature is nice to have
        // $quality = (int) $_post['imagequality'];

        $saveDir = dirname($this->baseDir . $this->folderName . $this->fileName);
        $saveFile = $this->createFileName($this->fileName);
        $fileSaveName = $saveDir. '/' . $saveFile;

        switch ($this->ext) {
            case 'jpg':
            case 'jpeg':
                $img_r = imagecreatefromjpeg($this->imageUrl);
                $dst_r = imagecreatetruecolor($targ_w, $targ_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                imagejpeg($dst_r, $fileSaveName, self::JPEG_QUALITY);
                break;
            case 'png':
                $img_r = imagecreatefrompng($this->imageUrl);
                $dst_r = imagecreatetruecolor($targ_w, $targ_h);
                imagealphablending($dst_r, false);
                imagesavealpha($dst_r, true);
                $transparent = imagecolorallocatealpha($dst_r, 255,255,255,127);
                imagefilledrectangle($dst_r, 0, 0, $_POST['x'], $_POST['y'], $transparent);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                imagepng($dst_r, $fileSaveName, self::PNG_QUALITY);
                break;
                /*
            case 'gif':
                $img_r = imagecreatefromgif($this->imageUrl);
                //$dst_r = imagecreatetruecolor($targ_w, $targ_h);
/*
                imagealphablending($dst_r, false);
                imagesavealpha($dst_r, true);
                $transparent = imagecolorallocatealpha($dst_r, 255,255,255,127);
                imagefilledrectangle($dst_r, 0, 0, $_POST['x'], $_POST['y'], $transparent);
                */
                /*
                imagesavealpha($img_r, true);
                imagecolortransparent($img_r, 127<<24);
                //imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                imagegif($img_r, $fileSaveName);
                break;
                */
            default:
                throw new Exception('Invalid File Type');
                break;
        }

        return $saveFile;
    }

    private function createFileName($fileName)
    {
        $ext = substr($fileName, strrpos($fileName, "."));
        return basename($fileName, $ext) . '_' . (int) $_POST['w'] . 'x' . (int) $_POST['h'] . $ext;
    }

    public function getWidth()
    {
        return $this->imageDimensions[0];
    }

    public function getHeight()
    {
        return $this->imageDimensions[1];
    }

    public function getUrl()
    {
        return $this->imageUrl;
    }

    public function getName()
    {
        return $this->fileName;
    }

    public function getFolderName()
    {
        return $this->folderName;
    }

    private function initializeVariables()
    {
        foreach (self::$variablesToInitialize as $var) {
            $this->initializeVariable($var); 
        }
    }

    private function initializeVariable($variableName)
    {
        if (isset($_GET[$variableName]) && !empty($_GET[$variableName])) {
            $this->{$variableName} = filter_var($_GET[$variableName], FILTER_SANITIZE_STRING);
        } elseif (isset($_POST[$variableName]) && !empty($_POST[$variableName])) {
            $this->{$variableName} = filter_var($_POST[$variableName], FILTER_SANITIZE_STRING);
        }
    }

    private function removeSlashes($variableName)
    {
        $this->{$variableName} = str_replace('/', '', $this->{$variableName}); 
    }

    private function initializeImageDimensions()
    {
        if (file_exists($this->imageUrl)) {
            $this->imageDimensions = getimagesize($this->imageUrl);
        }
    }

    private function initializeBaseDirectory()
    {
        // todo: make this dynamic
        $this->baseDir = 'D:/www/loews/d/';
    }

    private function initializeFileExtension()
    {
        $this->ext = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
    }

    private function getFileSaveName()
    {
        $saveDir = dirname($this->baseDir . $this->folderName . $this->fileName);
        $saveFile = $this->createFileName($this->fileName);
        return $saveDir. '/' . $saveFile;
    }
}
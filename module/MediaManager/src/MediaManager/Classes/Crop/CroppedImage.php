<?php
namespace MediaManager\Classes\Crop;

class CroppedImage
{   
	const JPEG_QUALITY         = 100;
	const PNG_QUALITY          = 9;
	const REGEX_CROP_SANITIZER = '/[^A-Za-z0-9 ._\/\\-]/';
	
	private $basicPath;
	private $imageName;
	private $newImageName;
	private $imagePath;
	private $srcPath;
	private	$src;
	private $savePath;
	private $ext;
	private $w;
	private $h;
	private $x;	
	private $y;

	public function __construct($post)
	{
		$imagePath               = $this->cropSanitizer($post['imagePath']);
		$imageName               = $this->cropSanitizer($post['imageName']);

		$this->imagePath         = $this->formatImagePath($imagePath);
		$this->imageName         = $imageName;
		
		$this->setBasicPath($imagePath);
		$this->createNewImageName();

		$this->srcPath           = SITE_PATH . '/' . $this->imagePath;
		$this->src               = $this->srcPath . $imageName;
		$this->ext               = strtolower(pathinfo($this->src, PATHINFO_EXTENSION));
		$this->savePath          = SITE_PATH . '/' . $this->imagePath . $this->newImageName;
		$this->w                 = (int) $post['w'];
		$this->h                 = (int) $post['h'];
		$this->x                 = (int) $post['x'];
		$this->y                 = (int) $post['y'];
	}	

	public function generateImage()
	{
		switch ($this->ext) {
			case 'jpg':
			case 'jpeg':
				$existingImage = imagecreatefromjpeg($this->src);
				$newImage = ImageCreateTrueColor($this->w, $this->h);
				imagecopyresampled($newImage, $existingImage, 0, 0, $this->x, $this->y, $this->w, $this->h, $this->w, $this->h);
				imagejpeg($newImage, $this->savePath, self::JPEG_QUALITY);
				$type = 'image/jpeg';
				break; 
			case 'png':
				$existingImage = imagecreatefrompng($this->src);
				$newImage = ImageCreateTrueColor($this->w, $this->h);
				imagealphablending($newImage, false);
				imagesavealpha($newImage, true);
				imagecopyresampled($newImage, $existingImage, 0, 0, $this->x, $this->y, $this->w, $this->h, $this->w, $this->h);
				imagepng($newImage, $this->savePath, self::PNG_QUALITY);
				$type = 'image/png';
				break;
			default:
				throw new \Exception('Invalid Image Type ' . $this->ext);
				break;
		}	

		$uploadData = array(
			'name'     => $this->newImageName,
			'origName' => $this->newImageName,
			'path'     => $this->basicPath,
			'tmpName'  => $this->newImageName,
			'type'     => $type
		);	

		return $uploadData;
	}

	private function cropSanitizer($field)
	{
		return preg_replace(self::REGEX_CROP_SANITIZER, '', $field);
	}

	private function setBasicPath($path)
	{
		if (substr($path, 0, 4) == 'd/d/') {
			$path = substr($path, 4);
		}
		if (substr($path, 0, 2) == 'd/') {
			$path = substr($path, 2);	
		}
		return $this->basicPath = $path;
	}

	private function createNewImageName()
	{
		$firstDotPosition   = strpos($this->imageName, '.');	
		$firstPart          = substr($this->imageName, 0, $firstDotPosition);
		$lastPart           = substr($this->imageName, $firstDotPosition);
		$this->newImageName = $firstPart . $this->generateRandomString() . $lastPart;
	}

	private function formatImagePath($path)
	{
		if (substr($path, 0, 4) == 'd/d/') {
			$path = substr($path, 2);
		}
		return $path;
	}

	private function generateRandomString($length = 8)
	{
		$characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}

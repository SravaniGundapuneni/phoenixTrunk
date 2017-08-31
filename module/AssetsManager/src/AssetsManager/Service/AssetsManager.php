<?php

/**
 * The Assets Manager Service File 
 *
 * @category    Toolbox
 * @package     Assets Manager Module 
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @author      Saurabh Shirgaonkar <sshirgaonkar@travelclick.com>
 * @filename    AssetsManager.php
 * @timestamp   7 Jan 2015, 3:34pm 
 */

namespace AssetsManager\Service;

class AssetsManager extends \ListModule\Service\Lists 
{  
	private $coreTemplateDirectory  = '/templates/main/core-templates/';
	private $fileType;
	private $mainTemplateDirectory  = '/templates/main/';
	private $pageName;
	private $sitePath;

	public function minifyCssAndJs($pageName)
	{
		$this->minifyCss($pageName);	
		$this->minifyJs($pageName);
	}

	public function minifyCss($pageName)
	{	
		$this->initSitePath();
		$this->setFileType('css');
		$this->setPageName($pageName);
		$this->minifyFiles();
	}
	
	public function minifyJs($pageName)
	{	
		$this->initSitePath();
		$this->setFileType('js');
		$this->setPageName($pageName);
		$this->minifyFiles();
	}

	// empty file, to avoid multiple appending:
	private function clearFile($fileToClear)
	{
		$clearedFile = fopen($fileToClear, 'w');
		fclose($clearedFile);
	}

	private function forwardifySlashes($stringWithBackslashes)
	{
		return str_replace('\\', '/', $stringWithBackslashes);
	}

	private function getBuffer($directoryName)
	{
		$buffer = '';
		$scannedWidgetDirectory = $this->getScannedWidgetDirectory($directoryName);
		$fileIterator           = new \DirectoryIterator($scannedWidgetDirectory);

		foreach ($fileIterator as $fileinfo) {
			if ($this->isValidFile($fileinfo)) {
				$buffer .= file_get_contents($scannedWidgetDirectory . $fileinfo->getFilename());
			}	
		}

		return $buffer;
	}

	private function getScannedWidgetDirectory($directoryName)
	{
		return $this->sitePath . $this->coreTemplateDirectory . $this->pageName . '/widgets/' . $directoryName . '/' . $this->fileType . '/';
	}

	private function getTemplateID()
	{   
		return $this->config->get(array('templateVars', 'templateID'));
	}

	private function getTemplateString()
	{
		return '-template' . $this->getTemplateID() . '.' . $this->fileType;
	}
	private function getWidgetDirectory()
	{
		return $this->sitePath . $this->coreTemplateDirectory . $this->pageName . '/widgets/';
	}

	private function getWriteFilePath()
	{
		return $this->sitePath . $this->mainTemplateDirectory . $this->fileType . '/' . $this->pageName . '.min.' . $this->fileType;                                                                         			
	}

	private function initSitePath()
	{
		$this->sitePath = $this->forwardifySlashes(SITE_PATH);
	}

	// filter out template-specific files that don't apply to the current template
	private function isFileCorrectTemplate($fileName)
	{
		$isCorrectTemplate = true;

		if (strpos($fileName, '-template')) {
			if (!strpos($fileName, $this->getTemplateString())) {
				$isCorrectTemplate = false;
			}	
		}	

		return $isCorrectTemplate;
	}

	private function isFileCorrectType($fileinfo)
	{
		return $fileinfo->getExtension() === $this->fileType;	
	}

	// When debug is true, minification should be false.
	private function isMinificationEnabled()
	{
		return $this->config->get(array('assetic_configuration', 'debug')) ? 0 : 1;
	}

	private function isValidDirectory($directoryName)
	{
		return substr($directoryName, 0, 1) !== '.';
	}

	private function isValidFile($fileinfo)
	{
		$isValid = true;

		if (!$fileinfo->isFile() || !$this->isFileCorrectType($fileinfo) || !$this->isFileCorrectTemplate($fileinfo->getFilename())) {
			$isValid = false;
		}

		return $isValid;
	}

	private function minifyFiles()
	{
		if ($this->pageExists()) {
			$this->writeFiles();
		}
	}

	private function pageExists()
	{
		return file_exists($this->sitePath . $this->coreTemplateDirectory . $this->pageName);
	}

	private function replaceComments($buffer)
	{
		//$expr = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\)\/\/.*))/'; <<< old regex, not sure what it does
		$inlineCommentRegex = '!/\*.*?\*/!s';
		$multiLineCommentRegex = '/\n\s*\n/';

		$buffer = preg_replace($inlineCommentRegex, '', $buffer);
		$buffer = preg_replace($multiLineCommentRegex, '', $buffer);

		return $buffer;
	}

	private function replacePaths($fileLine)
	{
		$target      = "../../../../../global";
		$replacement = "../global";				
		return str_replace($target, $replacement, $fileLine);
	}

	private function replaceSpaces($fileLine)
	{
		return str_replace('  ', '', $fileLine);									      
	}

	private function replaceTabs($fileLine)
	{
		return str_replace(array("\r", "\n","\v", "\f","\b", "\t"), '', $fileLine);
	}

	private function setFileType($fileType)
	{
		$this->fileType = $fileType;
	}

	private function setPageName($pageName)
	{
		$this->pageName = $pageName;
	}

	private function writeFile($options)
	{
		$writePath = $options['writePath'];
		$file      = $options['buffer'];

		if ($this->isMinificationEnabled() == 1) {
			$this->writeMinifiedFile($file, $writePath);
		} else {
			$this->writeUnminifiedFile($file, $writePath);
		}
	}

	private function writeFiles()
	{
		ini_set('pcre.backtrack_limit', 99999999999);
		$writeFilePath     = $this->getWriteFilePath();
		$directoryIterator = new \DirectoryIterator($this->getWidgetDirectory());

		$this->clearFile($writeFilePath);

		foreach ($directoryIterator as $dirinfo) {

			$directoryName = $dirinfo->getFilename();

			if ($this->isValidDirectory($directoryName)) {
				$this->writeFile(array(
					'buffer' => $this->getBuffer($directoryName),
					'writePath' => $writeFilePath
				));
			}
		}

		ini_set('pcre.backtrack_limit', 1000000);
	}

	private function writeMinifiedFile($buffer, $writePath)
	{
		$buffer = $this->replacePaths($buffer);
		$buffer = $this->replaceComments($buffer);
		$buffer = $this->replaceSpaces($buffer);
		$buffer = $this->replaceTabs($buffer);
		file_put_contents($writePath, $buffer, FILE_APPEND | LOCK_EX);
	}

	private function writeUnminifiedFile($buffer, $writePath)
	{
		$fileLine = $this->replacePaths($buffer);
		file_put_contents($writePath, $fileLine, FILE_APPEND | LOCK_EX);
	}
}

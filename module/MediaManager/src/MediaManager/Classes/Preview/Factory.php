<?php
namespace MediaManager\Classes\Preview;

class Factory
{
	public static function build($options)
	{
		switch ($options['previewType']) {
			case 'FOLDER':
				$preview = new Folder($options);
				break;
			case 'IMAGE':
				$preview = new Image($options);
				break;
			case 'VIDEO':
				$preview = new Video($options);
				break;
			case 'DOC':
				$preview = new Doc($options);
				break;
			default:
				throw new \Exception('Undefined Preview Type');
				break;	
		}

		return $preview;
	}	
}
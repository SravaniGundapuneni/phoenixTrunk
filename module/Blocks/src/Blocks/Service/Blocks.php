<?php
/**
 * The Blocks Service
 *
 * @category    Toolbox
 * @package     Blocks
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 * @filesource
 */
namespace Blocks\Service;

use Blocks\Entity;

/**
 * The Blocks Service
 *
 * @category    Toolbox
 * @package     Blocks
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Igor Sorokin <isorokin@travelclick.com>
 */

class Blocks extends \ListModule\Service\Lists
{
    static $svcLocator;
    static $conf;

    protected $entityName;
    protected $categories;
    protected $modelClass = "Blocks\Model\Block";

    public function __construct()
    {
        $this->entityName = "Blocks\Entity\Blocks";
    }
    
    //============================================================================================================
    // $id setter because the way it gets instantiated its value cannot be passed to the constructor
    /*
     * setController function
     * 
     * @access public
     * @static
     * @param mixed $cntr
     * @return void
     */
    public static function setController($cntr)
    {
        self::$svcLocator = $cntr->getServiceLocator();
        self::$conf = self::$svcLocator->get('MergedConfig');
    }
public function getPageOptions ()
    {
        $options = array();
         //inject default property as Not Assigned
        $options[0] = 'Not Assigned';
        $pages = $this->getDefaultEntityManager()->getRepository('Pages\Entity\Pages')->findBy(array('status' => 1));

        foreach ($pages as $index => $page) {
            echo $page->getName();
            $options[$page->getId()] = $page->getPageKey();
        }
        //var_dump($options);
        return $options;
    }
}

<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper; 
/**
 * MenuItem
 *
 * This is a major work in progress. Eventually the link and text filters need to be working,
 * but since this is just for Toolbox at the moment, I'm not too worried about it.
 *
 * @todo : get LINK and TEXT filters working
 * 
 */
class MenuItem extends AbstractHelper
{
    public function __invoke()
    {
        $parameters = func_get_args();
        //We'll allow both the associative method and just sending along the array.
        $menuItem = (isset($parameters[0]['menuItem'])) ? $parameters[0]['menuItem'] : $parameters[0];

        // print_r('<pre>');
        // print_r($menuItem);
        // print_r('</pre>');
        // die('FILE: ' . __FILE__ . ' LINE: ' . __LINE__);

        $viewVars = $this->getView()->vars();

        //For now we are operating under the assumption that toolbox is in english. 
        $url = isset($menuItem['link']) ? (string) $menuItem['link'] : $viewVars['toolboxHomeUrl'] . 'tools/' . $menuItem['key'] . '-en.html';

        $url = str_replace('#language', $viewVars['langCode'], $url);
        $last = (isset($menuItem['last']) && ($menuItem['last'])) ? ' last ' : '';
        $target = isset($menuItem['newWindow']) ? ' target="_blank"' : '';

        if (strpos($url, 'LINK default') !== false) {
            $url = $viewVars['siteBaseHREF'] . 'default-' . $viewVars['langCode'] . '.html';
        }

        $viewMenuItem = array(
                'url' => $url,
                'key' => $menuItem['key'],
                'last' => $last,
                'target' => $target
            );


        $this->getView()->menuItem = $viewMenuItem;

        return $this->getView()->render('toolbox-menu-item');
    }
}
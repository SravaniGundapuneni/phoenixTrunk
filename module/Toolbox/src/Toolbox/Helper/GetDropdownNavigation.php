<?php
namespace Toolbox\Helper;

use Zend\View\Helper\AbstractHelper;

class GetDropdownNavigation extends AbstractHelper
{
    /**
     * Build the dropdown navigation button
     * 
     * @param  integer $itemCount         The number of items in the menu
     * @param  string  $navigateDirection The navigational direction to render; 'up' or 'down'
     * @param  integer $itemLimit         The maximum number of items to show in the menu simultaneously
     * @return string                     The rendered dropdown naigation list element
     */
    public function __invoke($itemCount, $navigateDirection, $itemLimit = 12)
    {
        $elem = '';

        if ($navigateDirection !== 'up' && $navigateDirection !== 'down') {
            throw new Exception('Navigation direction must be either \'up\' or \'down\'.');
        }

        if ($itemCount > $itemLimit) {
            $elem = "<li class=\"dropdown-navigation-$navigateDirection text-center\" data-item-limit=\"$itemLimit\"><i class=\"fa fa-caret-$navigateDirection\"></i></li>";
        }

        return $elem;
    }
}
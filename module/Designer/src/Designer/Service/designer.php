<?php
/**
 * Description of Content
 *
 * @author isorokin
 */

namespace Designer\Service;

class Designer extends \Blocks\Service\Blocks
{
    //============================================================================================================
    public function setViewVars($view, $block)
    {
        $blockId = $block->getBlockKey();
/*        $view->jScript = "var $blockId=" . $block->getParameters();
        $view->jScript .= "
            createBubble($blockId);
            displayMap($blockId);
            displayTable($blockId);";
 * 
 */
        $view->toolboxIncludeUrl = self::$toolboxIncludeUrl;
        $view->blockId = $blockId;
    }
}

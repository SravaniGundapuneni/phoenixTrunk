<?php
namespace HeroImages\Helper;

use Zend\View\Helper\AbstractHelper;

class GetHeroImagePage extends AbstractHelper
{
    protected $pagesService;

    public function __construct($pagesService)
    {
        $this->pagesService = $pagesService;
    }

    public function __invoke($pageId)
    {
        $page = $this->pagesService->getItem($pageId);

        $pageLabel = '';

        if (!empty($page)) {
            $pageLabel = $page->getPagePath();
        }

        return $pageLabel;
    }
}
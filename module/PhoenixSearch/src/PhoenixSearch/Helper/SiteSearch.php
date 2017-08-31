<?php
namespace PhoenixSearch\Helper;

use Zend\View\Helper\AbstractHelper;

class SiteSearch extends AbstractHelper
{
    protected $polytextService;

    public function __invoke($for)
    {
        return $this->polytextService->searchNew($for);
    }

    public function __construct($polytextService)
    {
        $this->polytextService = $polytextService;
    }
}
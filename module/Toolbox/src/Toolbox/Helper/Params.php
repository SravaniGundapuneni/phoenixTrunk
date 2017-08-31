<?php

namespace Toolbox\Helper;

use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;
use Zend\View\Helper\AbstractHelper;

class Params extends AbstractHelper
{
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function fromGet($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->request->getQuery($param, $default)->toArray();
        }

        return $this->request->getQuery($param, $default);
    }

    public function fromPost($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->request->getPost($param, $default)->toArray();
        }

        return $this->request->getPost($param, $default);
    }
}
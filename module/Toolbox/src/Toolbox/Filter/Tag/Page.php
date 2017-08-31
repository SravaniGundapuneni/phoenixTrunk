<?php
namespace Toolbox\Filter\Tag;

class Page
{
    protected $config = array();

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    public function filter($value, $parameters = array())
    {
        switch($parameters[1]) {
            case 'addedHeaderContent':
                $value = "---addedHeaderContent---\n---headerMeta---";
                break;
            case 'addedBodyContent':
                $value = "---addedBodyContent---";
                break;
            case 'onloadScript':
                $value = "---onloadScript---";
                break;
            case 'inPageEditMenu':
                $value = "---inPageEditMenu---";
                break;
            default:
                $value = '';
                $value = $this->config->get($parameters[1], '');
        }

        return $value;
    }
}
<?php
namespace Toolbox\Filter\Tag;

class Link extends \Phoenix\Module\Filter\Tag\Filter
{
    protected $config = array();

    /**
     * Setup the filter tag
     *
     * @param array  $config
     */
    public function __construct($config = array())
    {
        $this->config = $config;
    }

    protected function parseUrl($url)
    {
        return $url=='self' ? null : $url;
    }

    protected function parseQuery($query)
    {
        $result = array();

        $parameters = explode('&', $query);
        foreach ($parameters as $key => $param)
        {
            $param = explode('=', $param);
            $paramName = isset($param[0]) ? $param[0] : null;
            $paramValue = isset($param[1]) ? $param[1] : null;

            if ($paramName) $result[$paramName] = $paramValue;
        }

        return $result;
    }

    protected function parseParameters($parameters)
    {
        $parameters = $this->removeTag($parameters);

        $url = $this->parseUrl(array_shift($parameters));
        $query = $this->parseQuery(array_shift($parameters));

        return array($url, $query);
    }

    public function filter($value, $parameters = array())
    {
        $parameters = $this->removeTag($parameters);

        $url = $this->parseUrl(array_shift($parameters));
        $query = array_shift($parameters);

        /**
         * We need to find the url view helper and use it instead;
         */
        return $url . '?' . $query;
    }
}
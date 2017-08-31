<?php
namespace Toolbox\Filter;

class ParseTags
{
    protected $config;

    protected $filters = array();
    protected $serviceManager;

    protected $unicodeTokenTable = array(
        'tagOpen'              => '!+',
        'escapedDoubleQuotes'  => '\"',
        'openingDoubleQuote'   => '"',
        'closingDoubleQuote'   => '"',
        'paramSeparator'       => ' ',
        'escapeWhitespace'     => ' ',
        'tagClose'             => '+!'
    );

    /**
     * Lets setup the ParseTags filter tag
     *
     * @param array $config
     * @param Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function __construct($config, $serviceManager)
    {
        $this->config         = $config;
        $this->serviceManager = $serviceManager;

        $this->filters = $config->get('phoenix-filters');

        /**
         * lets prepare our unicode token table
         */
        foreach ($this->unicodeTokenTable as $key => $value)
        {
            /**
             * Better way to get the current index
             */
            $index = isset($index) ? ++$index : 0;

            $this->unicodeTokenTable[$key] = array(
                "str" => $value,
                "uni" => dechex(57344 + $index)
            );
        }
    }

    /**
     * This is what Zend Calls when running the filterChain
     *
     * @param  string $contents
     * @return string
     */
    public function filter($contents)
    {
        $contents = $this->convertToTokens($contents, array('tagOpen', 'tagClose'));

        $tagOpen = $this->unicodeTokenTable['tagOpen']['uni'];
        $tagClose = $this->unicodeTokenTable['tagClose']['uni'];

        $patternToMatch = "\x{".$tagOpen."}([^\x{".$tagOpen."}\x{".$tagClose."}]*)\x{".$tagClose."}";

        /**
         * If we have a valid template tag lets call its filter handler
         */
        if ( preg_match_all("#{$patternToMatch}#ue", $contents, $templateTags) )
        {
            $contents = $this->filterTags($contents, $templateTags);
        }

        return $contents;
    }

    /**
     * Filter the template tags for the page
     *
     * @param  string $contents
     * @param  array $templateTags
     * @return string
     */
    protected function filterTags($contents, $templateTags)
    {
        $replaced = array();

        foreach ($templateTags[1] as $keyMatch => $valMatch)
        {
            if (isset($replaced[$valMatch])) continue;

            $replacementValue = $this->filterTag($valMatch);

            $contents = str_replace(
                $templateTags[0][$keyMatch],
                $replacementValue,
                $contents
            );

            $replaced[$valMatch] = true;
        }

        return $contents;
    }

    protected function filterTag($match)
    {
        $result = false;

        $parameters = $this->parseParameters($match);
        $filterName = strtolower(array_shift($parameters));
        $filterName = $this->filters[$filterName];

        /**
         * We'll allow both filters as services and instantiating new objects as filters.
         */
        if ($this->serviceManager->has($filterName))
        {
            $filterService = $this->serviceManager->get($filterName);
            $result = $filterService->filter($match, $parameters);
        }
        elseif (class_exists($filterName))
        {
            $filterService = new $filterName($this->config);
            $result = $filterService->filter($match, $parameters);
        }
        else
        {
            $result = false;
        }

        return $result;
    }

    protected function parseParameters($match)
    {
        $result = array();

        if ( $parameters = explode(' ', trim($match)) )
        {
            /**
             * Lets filter the parameters array from whitespace
             */
            $parameters = array_filter($parameters);

            foreach ($parameters as $key => $param)
            {
                if ( count($param = explode('=', $param)) == 2)
                {
                    $result[$param[0]] = trim(trim($param[1]),'\'"');
                }
                else
                {
                    $result[] = trim($param[0]);
                }
            }
        }

        return $result;
    }

    public function convertToTokens($contents, $tokens)
    {
        $result = $contents;

        $tokenTable = $this->unicodeTokenTable;

        $toReplace = array('to' => array(), 'with' => array());

        foreach ($tokens as $key => $token)
        {
            $str = $tokenTable[$token]['str'];
            $uni = $tokenTable[$token]['uni'];

            $toReplace['to'][]   = '/'.preg_quote($str,'/').'/u';
            $toReplace['with'][] = html_entity_decode('&#x'.$uni.';',null,'UTF-8');
        }

        /**
         * Do we have replacements
         */
        if ( $toReplace['to'] && $toReplace['with'] )
        {
            $result = preg_replace($toReplace['to'] , $toReplace['with'] , $contents);

            if (preg_last_error() > 0) {
                return $contents;
            }
        }

        return $result;
    }
}
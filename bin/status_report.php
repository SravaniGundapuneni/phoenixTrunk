<?php
/**
 * Status Report Checker
 *
 * This file checks the status reports for our toolbox sites
 *
 * @category    Toolbox
 * @package     System
 * @subpackage  Checker
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.TravelClick.com)
 * @license     All Rights Reserved
 * @version     Release: 12.x.x
 * @since       File available since release 12.x.x
 * @filesource
 */

$servercode = @$argv[1];
$sitecode = @$argv[2];

/**
 * Potential exit codes
 * @var array
 */
$exit_codes = array(
    0   => 'All sites are up',
    101 => 'Some sites have errors',
    999 => 'Fatal Error'
);

$api = 'http://www.online-toolbox.com/serverlists';
$sites = get_servers_sites($api,$servercode,$sitecode);
$status_report = status_report($sites);
echo $exit_codes[$status_report];
exit($status_report);

function get_servers($api)
{
    $list = @simplexml_load_file($api);
    return $list ? $list : array();
}

function get_servers_sites($api, $servercode, $sitecode)
{
    $servers_sites_entries = array();
    $serverlists = get_servers($api);
    foreach($serverlists as $key => $server)
    if ($servercode == (string) $server->a || !$servercode)
    if ($server && $listapi = $api.'/'.$server->a.'.html')
    if ($serversites = @simplexml_load_file($listapi))
    if ($serversites = $serversites->body->table->tr)
    foreach ($serversites as $site_array_type => $site)
    if ($sitecode == (string) $site->td[0] || !$sitecode)
    if ( (string) $site->td[1] != '__LIVE_SITE_URL__')
    $servers_sites_entries[] = (string) $site->td[1];

    return array_filter($servers_sites_entries);
}

/**
 * Clean up our site url
 * 
 * @param  string $site
 * @return string
 */
function cleanup($site)
{
    return rtrim(trim($site),'/');
}

/**
 * Get me all status codes for this urls
 * 
 * @param  array $urls
 * @return mixed
 */
function status_report($urls)
{
    /**
     * If its not an array or if empty
     * then return a fatal error
     */
    if (!is_array($urls)||empty($urls))
    {
        return 999;  
    }

    /**
     * Terminal colors
     */
    $colors = array(
        'txtblk' => "\033[0;30m", // Black - Regular
        'txtred' => "\033[1;31m", // Red
        'txtgrn' => "\033[1;32m", // Green
        'txtrst' => "\033[0m",    // Text Reset
    );

    /**
     * Calculate our needed string padding
     * @var int
     */
    $maxlen = max(array_map('strlen', $urls)) + 20;

    /**
     * Set our default exit code
     */
    $status_report = 0;

    /**
     * Lets check them urls
     */
    foreach ($urls as $url)
    {
        $color = $colors['txtgrn'];
        $reset = $colors['txtrst'];

        /**
         * make sure our urls start with the protocol schema
         */
        $url = preg_replace('/^(http:\/\/)?/','http://',$url);

        /**
         * Lets get the status code
         */
        $status = status_code($url);
        
        /**
         * Set color to red if bad result
         */
        if ($status >= 400)
        {
          $status_report = 101;
          $color = $colors['txtred'];
        }

        /**
         * Report back the status code for this url
         */
        $request = str_pad($url,$maxlen);
        $status = "{$color}{$status}{$reset}";
        echo "{$request} [{$status}]", PHP_EOL;
    }

    return $status_report;
}

/**
 * Gets the status code of a http request
 * 
 * @param  string $url
 * @return int
 */
function status_code($url)
{
    /**
     * Lets set some codes
     */
    $codes = array(
        'skiped' => '000',
        'failed' => '999'
    );

    if (substr($url,0,1)=='#') return $codes['skiped'];
        
    /**
     * Leverage HEAD request if php > 5.3.0
     */
    if (function_exists('stream_context_set_default'))
    {
        stream_context_set_default(
            array(
                'http' => array(
                    'method' => 'HEAD'
                )
            )
        );
    }

    $headers = @get_headers($url);
    $pattern = '/^(HTTP\/\d\.\d) (\d+) (.*)/';
    $headers = preg_filter($pattern,'$2',$headers);
    $status_code = @array_pop($headers);

    /**
     * Return our status code, or set to 999 when failed
     */
    return $status_code ? $status_code : $codes['failed'];
}
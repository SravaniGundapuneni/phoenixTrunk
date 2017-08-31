<?php
/**
 * Locates a site by sitecode
 *
 * Locates a site amongst all the production servers by sitecode
 *
 * @category    Toolbox
 * @package     System
 * @subpackage  Locator
 * @copyright   Copyright (c) 2011 EZYield.com, Inc (http://www.ezyield.com)
 * @license     All Rights Reserved
 * @version     Release: 1.0.0
 * @since       File available since release 1.0.0
 * @author      Jose A Duarte <jduarte@travelclick.com>
 * @filesource
 */

$sitecode = @$argv[1];

$api = 'http://www.online-toolbox.com/serverlists';
if (!$found = search_servers($api, $sitecode))
die("Sorry {$sitecode} was not found\n");

function get_servers($api)
{
	$list = @simplexml_load_file($api);
	return $list ? $list : array();
}

function get_servers_sites($api)
{
	$list = @simplexml_load_file($api);
	return $list ? $list : array();
}

function search_servers($api, $sitecode)
{
	$found_at_least_one_entry = false;
	$serverlists = get_servers($api);
	foreach($serverlists as $key => $server)
	if ($listapi = $api.'/'.$server->a.'.html')
	if ($serversites = get_servers_sites($listapi))
	if ($serversites = $serversites->body->table->tr)
	if ($site = search_sites($sitecode,$serversites))
	if (list($name,$url) = array($server->a,$site->td[1]))
	if ($found_at_least_one_entry = array($name,$url))
	echo "{$sitecode} => {$name} | http://{$url}\n";
	
	return $found_at_least_one_entry ? true : false;
}

/**
 * search_sites()
 * 
 * Searches the simpleXml dom for a given sitecode
 * 
 * @param  string $sitecode
 * @param  simpleXml $sites
 * @return mixed
 */
function search_sites($sitecode, $sites)
{
	if (!$sites) return null;
	foreach ($sites as $key => $site)
	if (site_matches($sitecode,$site))
	return $site;
}

/**
 * site_matches()
 * 
 * Compares a site record against the sitecode
 * 
 * @param  string $sitecode
 * @param  simpleXml $site
 * @return boolean
 */
function site_matches($sitecode, $site)
{
	if (!$site) return null;
	$isset = isset($site->td);
	$matches = $isset && $site->td[1] == $sitecode;
	$enabled = $isset && $site->td[6] == 'true';
	return $matches && $enabled;
}
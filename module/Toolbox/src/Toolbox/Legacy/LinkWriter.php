<?php
/**
 * Legacy Condor Link Writer Adapter
 *
 * This is the legacy adapter that corresponds to the condor CondorLinkWriter class.
 *
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 * @filesource
 */
namespace Toolbox\Legacy;

/**
 * Legacy Condor Link Writer Adapter
 *
 * This is the legacy adapter that corresponds to the condor CondorLinkWriter class.
 * 
 * Unlike Main, this extends from the current class. Piece by piece this will replace
 * the Condor Link Writer class.
 *
 * @category    Phoenix
 * @package     Application
 * @subpackage  Legacy
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Andrew C. Tate <atate@travelclick.com>
 */
class LinkWriter extends \CondorLinkWriter
{
    protected $newDb;
    protected $newAdminDb;
    protected $legacyDb;
    protected $canonicalUrl;

    public function setNewDb($newDb)
    {
        $this->newDb = $newDb;        
    }

    public function getNewDb()
    {
        return $this->newDb;
    }

    public function setNewAdminDb($newAdminDb)
    {
        $this->newAdminDb = $newAdminDb;        
    }

    public function getNewAdminDb()
    {
        return $this->newAdminDb;
    }

    public function setLegacyDb($legacyDb)
    {
        $this->legacyDb = $legacyDb;
    }

    public function getLegacyDb()
    {
        return $this->legacyDb;
    }    

    public function getCanonicalUrl()
    {
        return $this->canonicalUrl;
    }

    public function redirectByIncomingLink()
    {
        $legacyDb = $this->legacyDb;
        $escapedUri = $legacyDb->escape("http://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        if($legacyDb->tableExists('linkRedirects'))
        {
            $sql     = "SELECT * FROM linkRedirects WHERE BINARY incomingLink = '$escapedUri' AND status IN (".CONDOR_ITEM_STATUS_PUBLISHED.",".CONDOR_ITEM_STATUS_ARCHIVED.") LIMIT 1";
            $result = $legacyDb->query($sql, __FILE__, __LINE__);

            if($legacyDb->get_resultCount($result) == 1)
            {
                $statusStr = '';

                $row = $legacyDb->getAssocRow($result);

                switch($row['header']) {
                    case 301: $statusStr = 'HTTP/1.1 301 Moved Permanently'; break;
                    case 302: $statusStr = 'HTTP/1.1 302 Found'; break;
                    case 303: $statusStr = 'HTTP/1.1 303 See Other'; break;
                    case 307: $statusStr = 'HTTP/1.1 307 Temporary Redirect'; break;
                }

                $redirectUrls['linkRedirects'] = array(
                    'status'    => $statusStr,
                    'comment'    => 'Redirected_by: linkRedirects',
                    'location'    => "Location: {$row['redirectLink']}"
                );

                $legacyDb->close();

                //Why record finding a successful link in the missingLink table? 
                //Or rather, why let it continue to that part of the code, where it spews forth several MySQL errors into the logs?
                //Fixing this for now
                //Andrew C. Tate 05/01/2013
                
                return $redirectUrls;
            }
        }

        return false;    
    }
    
    public function compareUrls($requestedUrl, $canonicalUrl)
    {
        $requestedURL = preg_replace('/\?.*/', '', $requestedUrl);
        $canonicalURL = preg_replace('/\?.*/', '', $canonicalUrl);

        $requestedURLCmp = preg_replace('/^https?:\/\//', '', $requestedURL);
        $canonicalURLCmp = preg_replace('/^https?:\/\//', '', $canonicalURL);

        $requestedURLCmp = htmlentities($requestedURLCmp);

        return ($requestedURLCmp == $canonicalURLCmp);
    }

    public function canonicalizeURL($requestedURL, $main)
    {
        $queryParams = '';
        //We don't want the url canonicalized if this is a post.
        if ($main->getRequest()->isPost()) {
            return false;
        }

        $canonicalURL = $this->writeLinkTo("self");

        //If this identical to the canonical url, no need to continue here.
        if ($this->compareUrls($requestedURL, $canonicalURL)) {
            return false;
        }

        //If they don't match (ignoring the query strings), check whether the path in the URL
        // ends in “/” or is empty (ie www.myhotel.com). If so, redirect to canonical URL.
        $requestedURI = $this->getRelativeURL($requestedURL);

        $queryString = $this->getQueryString($requestedURL, false);
        $canonicalURL = $this->mergeQueryStringsUrl($canonicalURL, $queryString);

        if( empty($requestedURI) || $requestedURI === '/' )
        {
            if($main->homePageRedirection !== 'none')
            {
                // Turn-off auto-redirection for Websites with only 1 language
                // if(isset($main->text->langOptions) && count($main->text->langOptions)==1)
                // {
                //     return false;
                // }

                if($main->homePageRedirection === '301')
                {
                    $main->setHeader("HTTP/1.1 301 Moved Permanently"); 
                }
                else
                {
                    $main->setHeader("HTTP/1.1 302 Moved Temporarily");
                }

                $main->setHeader("Redirected_by: canonicalization (case without path)");
                $main->setHeader("Location: $canonicalURL");

                return true;
            }
        }
            
        if ( $requestedURI && $requestedURI !== '/' && $main->currentPage != '404' ) 
        {
            // Otherwise, redirect to the canonical URL, preserving the query string.
            $main->setHeader("HTTP/1.1 301 Moved Permanently");
            $main->setHeader("Redirected_by: canonicalization (case otherwise)");
            $main->setHeader("Location: $canonicalURL");
            
            return true;            
        }

        /* commented for future revision <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        // check if the url language is defined, if not, change with the default language
        if( !empty( $_GET['langCode'] ) && !isset($this->text->langOptionsFull[ $_GET['langCode'] ]) ) {
            $urlRewrite = $this->links->writeLinkTo( 'self', array('langCode' => (string) $this->text->defaultLanguage) );
            Header('DEFAULTLANG_:'. $this->text->defaultLanguage );
            header('Location: '. $urlRewrite);
            die;
        }
        
        // check if the pageAlias exists in the DB
        if( !empty($this->currentPageAlias) ) {
            $DBPageAlias = CondorLinkWriter::pageAlias_determineAlias($this->currentLocation, $this->currentPage, $_GET['langCode'], $this->currentTask, $this->currentItem);
            
            if( !empty($DBPageAlias) && $DBPageAlias != urldecode($this->currentPageAlias) .'-' )
            {
                Header('DBALIAS_:'. $DBPageAlias .'_END' );
                Header('PGALIAS_:'. $this->currentPageAlias .'_END' );
                header('Location: '. $this->links->writeLinkTo('self') );
                die;
            }
        }*/

    }

    /**
     * Return CONDOR and its parts (included seo url)
     * Condor URL format: <location>/[pageAlias-,]<page>[_taskPart][_itemPart][-$langCode].<$extension>
     * Condor URL parts : href - hrefSEO - location - pageAlias - pageAlias - page - task - item - langCode - extension
     * 
     * @global type $hosts
     * @param type $locationAndPage
     * @param type $addParams
     * @param type $skipSEO
     * @return array Array([0]=>CondorURL, [1]=>CondorURLParts)
     */
    public function writeLinksTo($locationAndPage, $addParams = array(), $skipSEO = false) 
    {
        global $main;
        $main = m();

        if (!is_array($addParams)) {
            trigger_error('Second argument $addParams for writeLinkTo must be an array', E_USER_WARNING);
        }

        //Perpetuate a direct task request
        if ($main->taskRequested() && $locationAndPage == 'self') {
            if (!isset($addParams['taskModule'])) {
                $addParams['taskModule'] = $_GET['taskModule'];
            }

            $addParams['taskName'] = isset($addParams['task']) ? $addParams['task'] : $_GET['taskName'];
        }

        $locationAndPage = trim($locationAndPage);
        $taskPart = '';
        $lang = '';
        $itemPart = '';

        // get module URL parameters
        if (isset($main->currentModuleObj->moduleCXML->links)) {
            foreach ($main->currentModuleObj->moduleCXML->links->perpetuateGETParam as $param) {
                $param = (string) $param;
                print_r($param . '<br>');
                if (isset($_GET[$param]) && !isset($addParams[$key])) {
                    $addParams[$param] = $_GET[$param];
                }
            }
        }

        $finalParams['langCode'] = isset($addParams['langCode']) ? $addParams['langCode'] : $main->text->language;
        $siteDomain = null;

        //Self should be remembering location -> page -> task -> item 
        if ($locationAndPage == 'self') {
            $finalParams['location'] = $main->currentLocation;
            $finalParams['page'] = $main->currentPage;

            if (isset($addParams['task']) and $addParams['task'] == 'null') {
                unset($addParams['task']);
            } else if ($main->currentTask) {
                $finalParams['task'] = $main->currentTask;
            }

            if (isset($addParams['item']) and $addParams['item'] == 'null') {
                unset($addParams['item']);
            } else if ($main->currentItem) {
                $finalParams['item'] = $main->currentItem;
            }
        } else {
            // for list module - if location starts with @ then write link to another domain
            if (substr($locationAndPage, 0, 1) == '@') {

                // identify proper domain    
                global $hosts;
                $primaryDomains = array();
                $pathElements = explode('/', trim($locationAndPage, '@'), 2);
                $siteFolder = $pathElements[0];

                foreach ($hosts AS $key => $host) {
                    if (($host['folder'] == $siteFolder) && ($host['primary'] == true))
                        $primaryDomains[] = "'" . $key . "'";
                }

                $locationAndPage = $pathElements[1];

            }

            if (mb_substr($locationAndPage, -1, 1) == '/') {
                $locationAndPage .= 'default';
            }
            if (mb_substr($locationAndPage, 0, 1) == '/') {
                $locationAndPage = mb_substr($locationAndPage, 1);
            }
            $infos = pathinfo($locationAndPage);
            $finalParams['location'] = ($infos['dirname'] == '.') ? '' : $infos['dirname'];
            $finalParams['page'] = $infos['basename'];
        }

        if (isset($addParams['location']) and $addParams['location'] != 'null') {
            $finalParams['location'] = $addParams['location'];
        }

        if (isset($addParams['page']) and $addParams['page'] != 'null') {
            $finalParams['page'] = $addParams['page'];
        }

        if (isset($addParams['task']) and $addParams['task'] != 'null') {
            $finalParams['task'] = $addParams['task'];
        }

        if (isset($addParams['item']) and $addParams['item'] != 'null') {
            $finalParams['item'] = $addParams['item'];
        }

        //put all the remaining addParams into the finalParams array
        foreach ($addParams as $key => $value) {
            if (!isset($finalParams[$key])) {
                $finalParams[$key] = $value;
            }
        }

        // Remove task if is the default task
        if (!empty($finalParams['task'])) {
            //Try to load the local CXML if page is an .html or .htm page
            $localCXML = preg_match('/(\.htm(l)?|\/)($|\?)/u', $_SERVER['REQUEST_URI']) ? getLocalCXML($finalParams['location'], $finalParams['page'], true) : false;
            // If local CXML cannot be found, else handle Page Not Found
            if($localCXML){
                $defaultTask = (string) $localCXML->content->conf->defaultTask;
                if(!empty ($defaultTask)){
                    if (mb_strpos($finalParams['task'], $defaultTask) !== false) {
                        unset($finalParams['task']);
                    }    
                }
            }
        }
        
        if (isset($main->localCXML->seo->sefURL) and (string) $main->localCXML->seo->sefURL == 'true') {
            if (isset($finalParams['item'])) {
                $itemPart = '_' . $finalParams['item'];
                $item = $finalParams['item'];
            } else {
                $item = false;
            }

            if (isset($finalParams['task'])) {
                $taskPart = '_' . $finalParams['task'];
                $task = $finalParams['task'];
            } else {
                $task = false;
            }

            $page = $finalParams['page'];
            $location = $finalParams['location'];
            $langCode = $finalParams['langCode'];

            unset($finalParams['item'], $finalParams['task'], $finalParams['page'], $finalParams['location'], $finalParams['langCode']);

            if (mb_strlen($location) and mb_substr($location, mb_strlen($location) - 1, 1) != '/') {
                $location .= '/';
            }

            // Code for backwards compatibility - i.e. specify htm instead of the default html... We probably need to discuss this, but here is a quick fix for the SEO's...
            $extension = $main->page->getVar("writeLinksExtension");
            if (mb_strlen($extension) == 0) {
                $extension = "html";
            }

            //Make it possible to use kind of virtual sub directories    
            if (defined('CONDOR_LINKS_BASE_LOCATION')) { // hostmap.ini sites
                $location = preg_replace('~^' . CONDOR_LINKS_BASE_LOCATION . '~', '', $location);
            }
            $pageAlias = '';
            $currentLanguageDomain = $main->text->getLanguageDomain($langCode);
            $isSingleLanguage = isset($main->text->langOptions) && count($main->text->langOptions) == 1;
            
            if( $page == 'default' && $isSingleLanguage && !$task && count($finalParams) == 0 ){
                $file = $fileSEO = $location;
                $query = '';
            } else {
                if (!$skipSEO) { // creates the 'pageAlias'
                    $pageAlias = $this->pageAlias_appendAlias($location, $page, $langCode, $task, $item);
                }
                $file = $location . $pageAlias . $page . $taskPart . $itemPart . "-$langCode.$extension";
                // For friendly functionallity. Doesn't save the alias in codorUrl field DB
                $fileWithoutAlias = $location . $page . $taskPart . $itemPart . "-$langCode.$extension";
                $query = (count($finalParams) > 0) ? '?' . http_build_query($finalParams) : '';
            }

            $hrefParts = array();
            $rootSite = self::getRootSite($langCode);
            $href = $rootSite . $file;

            if (!($page == 'default' && $isSingleLanguage)) {
                // Create a page with SEO friendly URL format
                $pageSEO = $page;

                if (!empty($task)) {
                    $pageSEO .= "/$task";
                }
                if (!empty($item)) {
                    $pageSEO .= "/$item";
                }
                if (!empty($pageAlias)) {
                    $pageAlias = str_replace('-,', '', $pageAlias);
                    $pageSEO .= "/" . $pageAlias . "-" . $page;
                }
                $fileSEO = "$langCode/" . $location . $pageSEO . ".$extension";

                $hrefParts['href'] = $fileWithoutAlias;
                $hrefParts['hrefSEO'] = $fileSEO;
                $folder = \HostMapProperties::getProperty($_SERVER['HTTP_HOST'], 'folder');
                if (!isset($folder) || '/' === $folder) {
                    $folder = '';
                }
                $hrefParts['folder'] = $folder;

                // DynamicPages module can adds condorUrl with parameters. 
                // Add LIKE in WHERE sentence because will not find the url with parameters.
                // Load SEO Friendly URL. 
                $sql = "SELECT * FROM seoUrls WHERE condorUrl LIKE '$fileWithoutAlias%' AND folder = '$folder' 
                AND status IN (".CONDOR_ITEM_STATUS_PUBLISHED.",".CONDOR_ITEM_STATUS_ARCHIVED.") 
                    order by created desc LIMIT 1";
                $result = $main->DB->query($sql, __file__, __line__);
                $row = $main->DB->getAssocRow($result);
                if ($row) {
                    if (isset($row['seoUrl']) && !empty($row['seoUrl'])) {
                        $href = $rootSite . $row['seoUrl'];
                    }
                }
            }

            $href .= $query;
        } else {
            $query = (count($finalParams) > 0) ? '?' . http_build_query($finalParams) : '';
            $href = SITE_BASE_HREF . 'index.php' . $query;
        }

        return array($href, $hrefParts);
    }    
}
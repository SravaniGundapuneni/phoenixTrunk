<?php
/**
 * Configuration file generated by Phoenix DevTools and ZFTool on 2014-01-24 10:51:13
 * The previous configuration file is stored in application.config.old
 *
 * @see https://github.com/zendframework/ZFTool
 */
/**
 * Adding this back in so doctrine-module works
 * 1234abcd
 */
 
PHP_SAPI=='cli' && define('SITE_PATH','public');
PHP_SAPI=='cli' && require 'init_autoloader.php';
    

return array(
    'country_list' => json_decode(file_get_contents(__DIR__ . '/countries.json')),
    'modules' => array(
                /**Thirt Party Tools*/
        
        //'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
        'EdpSuperluminal',
        'ModuleMigrations',
        /**Phoenix Modules*/
        'Toolbox',
        'PageCache',
        'Config',
        'Languages',
        'Users',
        'ListModule',
        'PhoenixProperties',
        'DynamicListModule',
        'ContentApproval',
        'PhoenixSocialToolbar',
        'PhoenixSearch',
        //'PhoenixEvents',
        'Pages',
        'Blocks',
        'PhoenixReview',
        'SiteMaps',
        //'ImageSwitch',
        'MediaManager',
        'Integration',
        'GooglePlaces',
        'Designer',
        'MapMarkers',
        'PhpSample',
        'SeoFileUpload',
        'FlexibleForms',
        'SeoMetaText',
        'HeroImages',
        'LinkRedirects',
        'Footer',
        'Navigations',
        'GoogleAnalytics',
        'AsseticBundle',
        'AssetsManager',       
         //'Weather',

        /** Phoenix Widgets */
        'Style',
        'LayoutEditor',
        'TemplateBuilder',
        'PhoenixTemplates',
        'LanguageNavigator',
        'Clock',
        'PartnersBar',
        'FooterMenu',
        'BreadCrumb',
        'TopMenu',
        'MailingList',
        //'GuestReviewsTemplate1',
        'Booking',
       // 'GuestReviews',
        //'Awards',
        'IHotelier',
        'Attractions',
        'ContactFooter',
        'RoomOffers',
        'GuestReviewsListView',
        'AwardsRotator',
        'AwardsListView',
        'CorporateLogo',
        'SpecialOffers',
        'MediaToolbar',
        //'TopMenu3',/*Widget for navigation for template 3*/
        'Calendar',
        'CalendarEvents',
        'MultiFeatureBlock',
        'MeetingRooms',
        'HeroImageWidget',
        'Press',
        'Careers',
        'NewSiteMapWidget',
        /** Here is Test Modules*/
        //'ConflictThis',
        //'Logo',
        //'LogoDemo',
        //'AndrewTest',
        // 'LogoTest',
        //'WeatherWidget',
    ),
    'early_module_listener_options' => array(
        'module_paths' => array(
            SITE_PATH . '/module',
            './EarlyPhoenix/module',
            './vendor',
        ),
        'config_glob_paths' => array('config/autoload/{,*.}{global,local}.php'),
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            SITE_PATH . '/module',
            './module',
            './vendor',
            './widget',
            SITE_PATH . '/widget',
            ),
        'config_glob_paths' => array('config/autoload/{,*.}{global,local}.php'),
        'module_map_cache_enabled' => true,
        // 'config_cache_enabled' => true,
        // 'config_cache_key' => 'itsus4you4ever',
        'module_map_cache_key' => 'hithere15atme',
        'cache_dir' => CACHE_PATH
        ),
    'cdn' => array(
        'username' => 'mjayachandran@travelclick.net',
        'password' => 'Welcome12',
        'statusChangeEmail' => 'htb-cdn@travelclick.com'
        ),
    'analytics' => array('gaMasterTrackingCode' => 'UA-32962968-1'),
    'paths' => array('serverScriptsDir' => '/var/www/shared/htb-ini/'),
    'session' => array(
        'name' => 'PHOENIXSESSIONID',
        'cleanUpProb' => 5
        )
    );

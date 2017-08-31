<?php
/**
 * Global Configuration Override
 *
 * NOTE: This is only for configuration that is general to Toolbox
 *
 * Eventually, there will be a sever.php and local.php, the replacements for local.ini and global.ini
 * The application will take those files, merge it with this file (and all of the module configs), and
 * that will be the config available to the Application.
 *
 * However, for backwards compatibility the initial version of the new Toolbox architecture is being written to
 * use local.ini, server.ini, and global.ini. Or rather, use the combined $iniSettings, which are still merged by
 * the code in each sites index.php. We can change that so new index files use a function from the new Toolbox to do the merging,
 * but we still need that backwards compatibility.
 */
 
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'htb_phxfoundation',
                    'charset'  => 'utf8',
                    'driverOptions' => array(
                           \PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8'
                    )                    
                ),
            ),
            'orm_admin' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'htb_toolboxadminphoenix',
                    'charset'  => 'utf8',
                    'driverOptions' => array(
                           \PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8'
                    )                    
                ),
            ),

        ),
    ),
);

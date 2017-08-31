<?php
/**
 * The migrations manager for phoenix
 *
 * This will handle migrating module tables to their latest versions.
 *
 * @category    Modules
 * @package     ModuleMigrations
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Jose A. Duarte <jduarte@travelclick.com>
 * @filesource
 */
namespace ModuleMigrations\Service;

use Phoenix\StdLib\ArrayHelper;
use Phoenix\Service\ServiceAbstract;

/**
 * The migrations manager for phoenix
 *
 * This will handle migrating module tables to their latest versions.
 *
 * @category    Modules
 * @package     ModuleMigrations
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      Jose A. Duarte <jduarte@travelclick.com>
 */
class ModuleMigrations extends ServiceAbstract
{
    protected static $_cachedTables = array();
    protected static $_checkedTables = array();

    protected function query($scope, $query)
    {
        $entityManager = $this->getEntityManager($scope);

        return $entityManager->getConnection()->query($query);
    }

    protected function fetchAll($scope, $query)
    {
        $entityManager = $this->getEntityManager($scope);

        return $entityManager->getConnection()->fetchAll($query);
    }

    public function tableExists($scope, $tableName)
    {
        $query = "SHOW TABLES LIKE '{$tableName}'";
        $exists = $this->fetchAll($scope, $query);

        return count($exists) ? true : false;
    }

    public function getEntityManager($scope)
    {
        $entityManager = null;

        if ($scope == 'default') $entityManager = $this->defaultEntityManager;
        if ($scope == 'admin') $entityManager = $this->adminEntityManager;

        return $entityManager;
    }

    public function getModuleTables($module)
    {
        $config = $module->getConfig();
        $namespace = $module->getNamespace();

        $moduleTables = ArrayHelper::getValueFromArray($config,'moduleTables', array());
        $moduleTables = ArrayHelper::getValueFromArray($moduleTables,$namespace, array());
 
        return $moduleTables;
    }

    public function getFieldDefinition($field)
    {
        $name = $field['Field'];
        $extra = $field['Extra'];

        /**
         * We dont need this information
         */
        unset($field['Field'],$field['Key']);

        if($field['Null'] == 'YES')
        {
            $field['Null'] = 'NULL';

            if($field['Default'] == 'CURRENT_TIMESTAMP')
            {
                $field['Default'] = 'DEFAULT CURRENT_TIMESTAMP';
            }
            elseif(isset($field['Default']))
            {
                $field['Default'] = "DEFAULT '{$field['Default']}'";
            }
            else
            {
                $field['Default'] = "DEFAULT NULL";
            }
        }
        else
        {
            $field['Null'] = 'NOT NULL';

            if($field['Default'] === 'CURRENT_TIMESTAMP')
            {
                $field['Default'] = 'DEFAULT CURRENT_TIMESTAMP';
            }
            elseif($field['Default'] != '')
            {
                $field['Default'] = "DEFAULT '{$field['Default']}'";
            }
            else
            {
                unset($field['Default']);
            }
        }

        $definition = trim(implode(' ', $field));
        $primarykey = $extra == 'auto_increment' ? 'PRIMARY KEY' : '';

        return "`{$name}` {$definition} {$primarykey}";
    }

    public function getTableFieldsDefinition($scope, $tableName)
    {
        $query = "SHOW FIELDS FROM `{$tableName}`;";
        $fields = $this->fetchAll($scope, $query);

        return $this->getDefinitionTableFields($fields);
    }

    public function getDefinitionTableFields($tableDefinitionFields)
    {
        $result = array();

        /**
         * Unset all the fields we dont care for;
         */
        foreach ($tableDefinitionFields as $fieldDefinition)
        {
            if ($fieldDefinition['Field'] instanceof \Zend\Config\Config) {
                $result[$fieldDefinition['Field']] = $fieldDefinition->toArray();
            } else {
                $result[$fieldDefinition['Field']] = $fieldDefinition;
            }

            unset($result[$fieldDefinition['Field']]['Key']);
        }

        return $result;
    }

    public function getTableIndexesDefinition($scope, $tableName)
    {
        $query = "SHOW INDEXES FROM `{$tableName}`;";
        $indexes = $this->fetchAll($scope, $query);

        return $this->getDefinitionTableIndexes($indexes);
    }

    public function getDefinitionTableIndexes($tableDefinitionIndexes)
    {
        $result = array();

        /**
         * Unset all the fields we dont care for;
         */
        foreach ($tableDefinitionIndexes as $indexDefinition)
        {
            /**
             * Lets ignore the PRIMARY INDEX
             */
            if ( $indexDefinition['Key_name'] !== 'PRIMARY' )
            {
                $result[$indexDefinition['Key_name']] = $indexDefinition;

                unset($result[$indexDefinition['Key_name']]['Table']);
                unset($result[$indexDefinition['Key_name']]['Seq_in_index']);
                unset($result[$indexDefinition['Key_name']]['Collation']);
                unset($result[$indexDefinition['Key_name']]['Cardinality']);
                unset($result[$indexDefinition['Key_name']]['Sub_part']);
                unset($result[$indexDefinition['Key_name']]['Packed']);
                unset($result[$indexDefinition['Key_name']]['Null']);
                unset($result[$indexDefinition['Key_name']]['Comment']);
                unset($result[$indexDefinition['Key_name']]['Index_comment']);
            }
        }

        return $result;
    }

    public function updateTableFieldsStructure($scope, $tableName, $definition)
    {
        $fieldsInTable = $this->getTableFieldsDefinition($scope, $tableName);

        $fieldsInDefinition = $this->getDefinitionTableFields($definition['fields']);

        foreach ($fieldsInDefinition as $fieldKey => $fieldDefinition)
        {
            /**
             * Lets remove the FOREING KEY from SchemaHelper::reference(...);
             */
            $fieldDefinition['Extra'] = current(explode(',',$fieldDefinition['Extra']));

            /**
             * If this fails either the field is a new field or is modified
             */
            if (array_search($fieldDefinition, $fieldsInTable, true) === false)
            {
                if ( $fieldKey && ! isset($fieldsInTable[$fieldKey]) )
                {
                    $query = $this->getFieldDefinition($fieldDefinition);
                    $query = "ALTER TABLE `{$tableName}` ADD {$query};";
                    $result = $this->query($scope, $query);
                }
                else
                {
                    $query = $this->getFieldDefinition($fieldDefinition);
                    $query = "ALTER TABLE `{$tableName}` MODIFY {$query};";
                    $result = $this->query($scope, $query);
                }
            }
        }

        return true;
    }

    public function updateTableIndexesStructure($scope, $tableName, $definition)
    {
        $indexesInTable = $this->getTableIndexesDefinition($scope, $tableName);
        $indexesInDefinition = $this->getDefinitionTableIndexes($definition['indexes']);

        foreach ($indexesInDefinition as $indexKey => $indexDefinition)
        {
            if (array_search($indexDefinition, $indexesInTable) === false)
            {
                if ( $indexKey && ! isset($indexesInTable[$indexKey]) )
                {
                    $type = $indexDefinition['Index_type']!=='BTREE'?$indexDefinition['Index_type']:'';
                    $query = "`{$indexDefinition['Key_name']}` (`{$indexDefinition['Column_name']}`)";
                    $query = "ALTER TABLE `{$tableName}` ADD {$type} INDEX {$query};";
                    $result = $this->query($scope, $query);
                }
                else
                {
                    // MODIFYING INDEXES IS NOT IMPLEMENTED YET
                }
            }
        }

        return true;
    }

    public function updateTableStructure($scope, $tableName, $definition)
    {
        $updated = array();

        $updated[] = $this->updateTableFieldsStructure($scope, $tableName, $definition);
        $updated[] = $this->updateTableIndexesStructure($scope, $tableName, $definition);

        return array_filter($updated) ? true : false;
    }

    public function createTableStructure($scope, $tableName, $definition)
    {
        $entityManager = $this->getEntityManager($scope);

        $fields = ArrayHelper::getValueFromArray($definition,'fields',array());
        $indexes = ArrayHelper::getValueFromArray($definition,'indexes',array());
        $engine = ArrayHelper::getValueFromArray($definition,'engine','MyISAM');
        $autoIncrement = ArrayHelper::getValueFromArray($definition, 'autoIncrement', 1);

        foreach ($fields as $fieldTableIndex => $fieldDefinition)
        {
            $tableFieldDefinitions[] = $this->getFieldDefinition($fieldDefinition);
        }

        // foreach ($indexes as $indexTableIndex => $indexDefinition)
        // {
        //     $tableIndexDefinitions[] = $this->getIndexDefinition($indexDefinition);
        // }

        $fields = implode(',',$tableFieldDefinitions);
        // $indexes = implode(',',$tableIndexDefinitions);

        $engine = strpos($fields,'FOREIGN') ? 'InnoDB' : $engine;
        $metadata = "ENGINE={$engine} DEFAULT CHARSET=utf8 AUTO_INCREMENT={$autoIncrement}";
        $query = "CREATE TABLE `{$tableName}` ({$fields}) {$metadata};";

        $entityManager->getConnection()->query($query);

        return true;
    }

    /**
     * updateModuleTables()
     *
     * Updates all the tables for a given module
     *
     * @param  object $module
     * @return void
     */
    public function updateModuleTables($module)
    {
        $moduleNamespace = $module->getNamespace();
        $moduleTables = $this->getModuleTables($module);

        /**
         * Iterate thru all the database scopes and update tables
         */
        foreach ($moduleTables as $tableName => $tableScopes)
        {
            foreach ($tableScopes as $scope => $tableDefinition)
            {
                if ( ! isset($this::$_checkedTables[$moduleNamespace][$scope][$tableName]) )
                {
                    if ( ! $this->tableExists($scope, $tableName) )
                    {
                        $this->createTableStructure($scope, $tableName, $tableDefinition);
                    }
                    else
                    {
                        $this->updateTableStructure($scope, $tableName, $tableDefinition);
                    }

                    $this::$_checkedTables[$moduleNamespace][$scope][$tableName] = true;
                }
            }
        }

        return true;
    }
}
<?php

/**
 * The file for the FlexibleForms Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */

namespace FlexibleForms\Service;

use FlexibleForms\Model\Module;
use ListModule\Service\Lists;
use Phoenix\Service\ServiceAbstract;

/**
 * The FlexibleForms Class for the Dynamic List Module
 *
 * @category    Toolbox
 * @package     FlexibleForms
 * @subpackage  Service
 * @copyright   Copyright (c) 2014 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 1.14
 * @since       File available since release 1.14
 * @author      A. Tate <atate@travelclick.com>
 */
class FlexibleForms extends Lists {

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = MODULE::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\FlexibleForms\Model\Module";
    
    // TODO: Doctrinify this
    public function getFormFields($formName)
    {
        return $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT
                *
            FROM
                flexibleForm

            INNER JOIN
                flexibleForms_fields
            ON
                flexibleForm.formId = flexibleForms_fields.form
            WHERE
                flexibleForm.name = '$formName'
        ");
    }

    public function getModuleItems($formId)
    {
        $items = $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT
                fi.itemId,
                group_concat(fif.value) AS value
            FROM 
                flexibleforms_items fi 
            LEFT JOIN
                flexibleforms_itemfields fif 
            ON
                fi.itemId = fif.item
            WHERE
                fi.form = $formId
            GROUP BY 
                fif.item
        ");


        return $items;
    }

    public function getItemsByName($formName)
    {
        $field = $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT
                formId
            FROM
                flexibleForm 
            WHERE
                name = '$formName'
            ");
        
        $formId = ($field[0]['formId']);
        
        $items = $this->defaultEntityManager->getConnection()->fetchAll("
            SELECT
                ff.displayName,
                ff.name
            FROM
                flexibleForm fi 
            LEFT JOIN
                flexibleForms_fields ff
            ON
                fi.formId = ff.form
            WHERE
                fi.formId = $formId
            ");

        //Return the fields collection
        return $items;
    }

}

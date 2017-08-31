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

use FlexibleForms\Model\Views;
use ListModule\Service\Lists;

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
class View extends Lists {

    /**
     * The name of the entity used by the associated model
     * @var string
     */
    protected $entityName = Views::ENTITY_NAME;

    /**
     * The name of the model class associated with this service
     * @var string
     */
    protected $modelClass = "\FlexibleForms\Model\Views";

    public function userProperty() {

        $currentUser = $this->getCurrentUser();

        $aclService = $this->getServiceManager()->get('phoenix-users-acl');

        if ($aclService->isUserAllowed($this->currentUser, null, \Users\Service\Acl::PERMISSIONS_RESOURCE_DEVELOP)) {
            $userValue = 1;
        } else {
            $userValue = 0;
        }
        return $userValue;
    }

    public function getFormItems($formId) {

        $items = $this->defaultEntityManager->getConnection()->fetchAll("select
            fi.itemId, group_concat(fif.value SEPARATOR ',.,.,') as value
            from flexibleForms_items fi 
            left join flexibleForms_itemFields fif on
            fi.itemId = fif.item
            where fi.form = $formId
            group by fif.item");

        return $items;
    }

    public function getFields($formId) {
        $field = $this->defaultEntityManager->getConnection()->fetchAll("select
           ff.displayName
            from flexibleForm fi 
            left join flexibleForms_fields ff on
            fi.formId= ff.form
            where fi.formId = $formId");

        //Return the fields collection
        return $field;
    }

}

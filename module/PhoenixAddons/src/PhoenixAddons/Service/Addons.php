<?php

namespace PhoenixAddons\Service;

use PhoenixAddons\Model\Addon;
use PhoenixAddons\Entity\PhoenixAddon;
use PhoenixProperties\Service\SubmoduleUnifiedAbstract;

class Addons extends SubmoduleUnifiedAbstract
{
    protected $orderList = true;
    /**
     * __construct
     *
     * Construct our addons service
     *
     * @return void
     */
    public function __construct()
    {
        $this->entityName = Addon::ENTITY_NAME;
        $this->modelClass = "\PhoenixAddons\Model\Addon";
    }

    /**
     * This method is called from the parent class and adds ordering by 'featured' field to the query
     * @param type $qb
     */
    protected function modifyQuery(&$qb)
    {
        $qb->orderBy('pp.featured DESC, pp.property ASC, pp.name ASC');
    }

    /**
     * getAddon
     *
     * An alias of getItem
     *
     * @param  integer $selector
     * @return \PhoenixAddons\Model\Addon
     */
    public function getAddon($selector)
    {
        $result = null;

        if (is_numeric($selector) && intval($selector)) {
            $result = $this->getItem($selector);
        } elseif (is_string($selector) && strlen($selector)) {
            $result = $this->getItemBy(array('code' => $selector));
        } elseif (is_array($selector) && $selector) {
            $result = $this->getItemBy($selector);
        }

        return $result;
    }

    public function createAddon($data, $save = false)
    {
        $status = Addon::DEFAULT_ITEM_STATUS;
        
        $entityModel = $this->createModel();
        $entityModel->setEntity(new PhoenixAddon);
        $entityModel->getEntity()->setStatus($status);
        $entityModel->loadFromArray($data);
        if ($save)
            $entityModel->save();
        return $entityModel;
    }

    public function save($model, $data)
    {
        if (!isset($data['component'])) {
            $data['component'] = $this->getModule();
        }
        if (!$model->getUserModified()) {
            $data['userModified'] = $this->hasChanges($model, $data);
        }

        parent::save($model, $data);
    }

}

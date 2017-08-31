<?php
namespace PhoenixSite\Widget;

class DataManager
{
    protected $dataInstance = array();

    public function addInstance($name, $dataInstance = null)
    {
        if (empty($dataInstance) || !$dataInstance instanceof DataInstance) {
            $dataInstance = new DataInstance();
        }

        $this->dataInstance[$name] = $dataInstance;

        return $this->getInstance($name);
    }

    public function removeInstance($name)
    {
        unset($this->dataInstance[$name]);
    }

    public function getInstance($name)
    {
        return empty($this->dataInstance[$name]) ? false : $this->dataInstance[$name];
    }
}
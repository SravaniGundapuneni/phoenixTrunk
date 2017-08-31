<?php
namespace PageCache\Service;

use Phoenix\Service\ServiceAbstract;

class PageCache extends ServiceAbstract
{
    public function refreshCache()
    {
        $defaultEntityManager = $this->getDefaultEntityManager();

        $connection = $defaultEntityManager->getConnection();

        $connection->executeUpdate('TRUNCATE TABLE cache');           
    }
}
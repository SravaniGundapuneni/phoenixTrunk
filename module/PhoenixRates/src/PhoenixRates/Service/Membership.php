<?php
/**
 * The PhoenixRates Service
 *
 * @category    Toolbox
 * @package     PhoenixRates
 * @subpackage  Service
 * @copyright   Copyright (c) 2013 TravelClick, Inc (http://www.travelclick.com)
 * @license     All Rights Reserved
 * @version     Release: 13.4
 * @since       File available since release 13.4
 * @author      A. Tate <atate@travelclick.com>
 * @filesource
 */
namespace PhoenixRates\Service;

class Membership extends \ListModule\Service\Lists
{
    public function setMembershipLevelOptions($membershipField)
    {
        $membershipLevels = $this->getMemberships();

        $options = array();
        foreach ($membershipLevels as $level) {
            $options[$level['id']] = $level['name'];
        }

        $membershipField->setValueOptions($options);
    }

    public function getMemberships()
    {
        return $this->getDefaultEntityManager()->createQueryBuilder()
            ->select('ms')
            ->from('PhoenixRates\Entity\Membership', 'ms')
            ->getQuery()->getArrayResult();
    }
}
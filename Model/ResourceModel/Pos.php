<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Pos extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('pos', 'pos_id');
    }
}

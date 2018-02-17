<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Model;

use Magento\Framework\Model\AbstractModel;

class Pos extends AbstractModel
{

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Ocheretnyi\Pos\Model\ResourceModel\Pos');
    }
}

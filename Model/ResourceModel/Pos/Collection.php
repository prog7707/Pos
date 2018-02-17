<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Model\ResourceModel\Pos;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Ocheretnyi\Pos\Model\Pos',
            'Ocheretnyi\Pos\Model\ResourceModel\Pos'
        );
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->setOrder('name', 'asc');
        $options = [];
        foreach ($collection as $item) {
            $options[] = ['value' => $item->getPosId(), 'label' => $item->getName()];
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getPosList()
    {
        $collection = $this->setOrder('name', 'asc');
        $options = [];
        foreach ($collection as $item) {
            $options[$item->getPosId()] = $item->getName();
        }
        return $options;
    }
}

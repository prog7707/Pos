<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Model\Config\Source;

class Pos implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory
     */
    protected $_posCollectionFactory;

    /**
     * Options array
     *
     * @var array
     */
    protected $_options;

    /**
     * Pos constructor.
     *
     * @param \Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory $posCollectionFactory
     */
    public function __construct(\Ocheretnyi\Pos\Model\ResourceModel\Pos\CollectionFactory $posCollectionFactory)
    {
        $this->_posCollectionFactory = $posCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = $this->_posCollectionFactory->create()->toOptionArray();
        }

        return $this->_options;
    }
}

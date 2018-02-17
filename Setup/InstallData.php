<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

namespace Ocheretnyi\Pos\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * InstallData constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $data = [
            [
                'store_id'      => $storeId,
                'name'          => 'London',
                'primary_email' => 'primary_london@gmail.com',
                'support_email' => 'support_london@gmail.com',
                'description'   => 'the best London POS'
            ],
            [
                'store_id'      => $storeId,
                'name'          => 'New York',
                'primary_email' => 'primary_ny@gmail.com',
                'support_email' => 'support_ny@gmail.com',
                'description'   => 'the best NY POS'
            ],
            [
                'store_id'      => $storeId,
                'name'          => 'Boston',
                'primary_email' => 'primary_boston@gmail.com',
                'support_email' => 'support_boston@gmail.com',
                'description'   => 'the best Boston POS'
            ]
        ];

        foreach ($data as $bind) {
            $setup->getConnection()->insertForce($setup->getTable('pos'), $bind);
        }
    }
}

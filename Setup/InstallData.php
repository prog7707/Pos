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
     * Post factory
     *
     * @var \Ocheretnyi\Pos\Model\Pos
     */
    protected $_posModel;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * InstallData constructor.
     *
     * @param \Ocheretnyi\Pos\Model\Pos                  $posModel
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Ocheretnyi\Pos\Model\Pos $posModel,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_posModel = $posModel;
        $this->_storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $data = [
            [
                'pos_id'        => null,
                'store_id'      => $storeId,
                'name'          => 'London',
                'primary_email' => 'primary_london@gmail.com',
                'support_email' => 'support_london@gmail.com',
                'description'   => 'the best London POS'
            ],
            [
                'pos_id'        => null,
                'store_id'      => $storeId,
                'name'          => 'New York',
                'primary_email' => 'primary_ny@gmail.com',
                'support_email' => 'support_ny@gmail.com',
                'description'   => 'the best NY POS'
            ],
            [
                'pos_id'        => null,
                'store_id'      => $storeId,
                'name'          => 'Boston',
                'primary_email' => 'primary_boston@gmail.com',
                'support_email' => 'support_boston@gmail.com',
                'description'   => 'the best Boston POS'
            ]
        ];

        foreach ($data as $item) {
            $this->_posModel->setData($item)->save();
        }
    }
}
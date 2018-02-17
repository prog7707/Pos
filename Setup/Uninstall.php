<?php
/**
 * Simplified POS module for Magento
 *
 * @package     Ocheretnyi_Pos
 * @author      Igor Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @copyright   Copyright 2018 Ocheretnyi (https://www.linkedin.com/in/iocheretnyi)
 * @license     Open Source License (OSL v3)
 */

declare(strict_types=1);

namespace Ocheretnyi\Pos\Setup;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Config\Model\ResourceModel\Config\Data;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{

    /**
     * @var CollectionFactory
     */
    public $collectionFactory;
    /**
     * @var Data
     */
    public $configResource;

    /**
     * @param CollectionFactory $collectionFactory
     * @param Data              $configResource
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $configResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->configResource = $configResource;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Generic.CodeAnalysis.UnusedFunctionParameter)
     */
    // @codingStandardsIgnoreStart
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        //remove tables
        if ($setup->tableExists('pos')) {
            $setup->getConnection()->dropTable('pos');
        }
        //remove config settings if any
        $collection = $this->collectionFactory->create()
            ->addPathFilter('ocheretnyi_pos');
        foreach ($collection as $config) {
            $this->deleteConfig($config);
        }
    }

    /**
     * @param AbstractModel $config
     *
     * @throws \Exception
     */
    public function deleteConfig(AbstractModel $config)
    {
        $this->configResource->delete($config);
    }
}

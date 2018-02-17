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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return $this|void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /*
         * Create table 'pos'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('pos')
        )->addColumn(
            'pos_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'POS id'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            5,
            ['nullable' => false, 'default' => 0],
            'Store id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            150,
            ['nullable' => false, 'default' => ''],
            'POS name'
        )->addColumn(
            'primary_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['nullable' => false, 'default' => ''],
            'Primary email'
        )->addColumn(
            'support_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['nullable' => false, 'default' => ''],
            'Support Email'
        )->addColumn(
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false, 'default' => ''],
            'Description'
        )->setComment(
            'POS Table'
        );

        $setup->getConnection()->createTable($table);

        /**
         * Add pos_id fields in order tables
         */
        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order'),
            'pos_id',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'POS id'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_grid'),
            'pos_id',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'comment'  => 'POS id'
            ]
        );
    }
}
